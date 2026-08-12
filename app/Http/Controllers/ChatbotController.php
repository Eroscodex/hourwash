<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Machine;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\QrCode;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $msg = strtolower(trim($request->message));

        // 1. Guardrail Check
        if (Str::contains($msg, [
            'pancit',
            'cook',
            'recipe',
            'food',
            'noodle',
            'dish',
            'ingredient',
            'python',
            'code',
            'math',
            'politic',
        ])) {
            return response()->json([
                'reply' => 'I am the HourWash AI Assistant, specialized exclusively for Hour Wash Laundry Shop in Magallanes St., Orosite, Legazpi City! I can help you track laundry orders, check operating hours (7:00 AM – 6:00 PM), or inspect service packages and rates. How can I assist with your laundry today?',
            ]);
        }

        /*
         * IMPORTANT:
         * Handle order lookups BEFORE the LLM.
         *
         * This prevents the AI from interpreting:
         * "the order of karl nicko"
         * as the customer's actual name.
         */
        if ($this->isOrderRequest($msg)) {
            return response()->json([
                'reply' => $this->getOrderLookupReply($msg),
            ]);
        }

        // 2. Build live database context for the LLM
        $systemPrompt = $this->buildSystemPrompt();

        // 3. OpenAI
        $openAiKey = env('OPENAI_API_KEY');

        if (!empty($openAiKey)) {
            try {
                $response = Http::timeout(8)
                    ->withToken($openAiKey)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $systemPrompt,
                            ],
                            [
                                'role' => 'user',
                                'content' => $request->message,
                            ],
                        ],
                        'max_tokens' => 300,
                    ]);

                if ($response->successful()) {
                    $reply = $response->json('choices.0.message.content');

                    if (
                        !empty($reply) &&
                        !Str::contains(strtolower($reply), [
                            'pancit',
                            'cook',
                            'recipe',
                        ])
                    ) {
                        return response()->json([
                            'reply' => $reply,
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                // Continue to Ollama/fallback engine
            }
        }

        // 4. Local Ollama
        try {
            $response = Http::timeout(4)
                ->post('http://127.0.0.1:11434/api/chat', [
                    'model' => 'gemma3:1b',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $systemPrompt,
                        ],
                        [
                            'role' => 'user',
                            'content' => $request->message,
                        ],
                    ],
                    'stream' => false,
                ]);

            if ($response->successful()) {
                $reply = $response->json('message.content');

                if (
                    !empty($reply) &&
                    !Str::contains(strtolower($reply), [
                        'pancit',
                        'cook',
                        'recipe',
                    ])
                ) {
                    return response()->json([
                        'reply' => $reply,
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Continue to fallback engine
        }

        // 5. Fallback
        return response()->json([
            'reply' => $this->getDomainReply($msg),
        ]);
    }

    /**
     * Determine whether the user is asking about an order.
     */
    private function isOrderRequest(string $msg): bool
    {
        return Str::contains($msg, [
            'my order',
            'my laundry',
            'check order',
            'track order',
            'order status',
            'order of',
            'order for',
            'laundry status',
            'status of my order',
            'status of',
        ])
        || preg_match('/\bhw-?[a-z0-9]+\b/i', $msg)
        || preg_match('/[0-9a-f]{8}-[0-9a-f]{4}/i', $msg);
    }

    /**
     * Handle order lookup directly from the database.
     */
    private function getOrderLookupReply(string $msg): string
    {
        // ---------------------------------------------------------
        // 1. ORDER NUMBER
        // ---------------------------------------------------------

        if (
            preg_match('/\b(hw-?[a-z0-9]+)\b/i', $msg, $matches)
            || preg_match('/\b([0-9a-f]{8}-[0-9a-f]{4})\b/i', $msg, $matches)
        ) {
            $code = strtoupper(trim($matches[1]));

            $qr = QrCode::where('qr_token', $code)->first();

            $order = null;

            if ($qr) {
                $order = Order::with(['service', 'customer'])
                    ->find($qr->order_id);
            }

            if (!$order) {
                $order = Order::with(['service', 'customer'])
                    ->where('order_number', $code)
                    ->first();
            }

            if ($order) {
                return $this->formatOrder($order);
            }

            return "I couldn't find an order with number {$code}. Please check the order number and try again.";
        }

        // ---------------------------------------------------------
        // 2. EMAIL
        // ---------------------------------------------------------

        if (
            preg_match(
                '/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/',
                $msg,
                $emailMatch
            )
        ) {
            $email = strtolower($emailMatch[0]);

            $user = User::whereRaw('LOWER(email) = ?', [$email])
                ->whereIn('role', ['customer', 'user'])
                ->first();

            if ($user) {
                return $this->getCustomerOrderSummary($user);
            }

            return "I couldn't find an account with email {$email}. Please double-check your email address or provide your order number (e.g. HW-XXXXXXXX).";
        }

        // ---------------------------------------------------------
        // 3. CUSTOMER NAME
        // ---------------------------------------------------------

        $name = $this->extractCustomerName($msg);

        if ($name) {
            $user = $this->findCustomerByName($name);

            if ($user) {
                return $this->getCustomerOrderSummary($user);
            }

            return "I couldn't find a customer named \"{$name}\". Please check the spelling or provide your order number (e.g. HW-XXXXXXXX).";
        }

        return "To check your laundry order status, please provide one of the following:\n"
            . "- Your order number (e.g. HW-XXXXXXXX)\n"
            . "- Your registered email\n"
            . "- The name on your account";
    }

    /**
     * Extract a customer's name from common natural-language requests.
     *
     * Examples:
     * "the order of Karl Nicko"
     * "check order for Karl Nicko"
     * "track Karl Nicko's order"
     * "order status Karl Nicko"
     */
    private function extractCustomerName(string $msg): ?string
    {
        $patterns = [
            // "the order of Karl Nicko"
            '/\b(?:the\s+)?order\s+of\s+([a-z][a-z\s.\'-]+?)(?:\'s)?$/i',

            // "order for Karl Nicko"
            '/\border\s+for\s+([a-z][a-z\s.\'-]+?)(?:\'s)?$/i',

            // "check order for Karl Nicko"
            '/\b(?:check|track|find|show)\s+(?:my\s+)?order\s+for\s+([a-z][a-z\s.\'-]+?)(?:\'s)?$/i',

            // "Karl Nicko's order"
            '/\b([a-z][a-z\s.\'-]+?)\'s\s+order\b/i',

            // "order status Karl Nicko"
            '/\border\s+status\s+(?:for\s+)?([a-z][a-z\s.\'-]+)$/i',

            // "name is Karl Nicko"
            '/\bname\s+is\s+([a-z][a-z\s.\'-]+)$/i',

            // "named Karl Nicko"
            '/\bnamed\s+([a-z][a-z\s.\'-]+)$/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $msg, $matches)) {
                $name = trim($matches[1]);

                // Remove common words accidentally captured
                $name = preg_replace(
                    '/^(the|my|order|of|for)\s+/i',
                    '',
                    $name
                );

                $name = preg_replace(
                    '/\s+(order|status)$/i',
                    '',
                    $name
                );

                $name = trim($name);

                if (strlen($name) >= 2) {
                    return $name;
                }
            }
        }

        return null;
    }

    /**
     * Find customer using flexible name matching.
     */
    private function findCustomerByName(string $name): ?User
    {
        $name = trim($name);

        // Exact case-insensitive match first
        $user = User::whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->whereIn('role', ['customer', 'user'])
            ->first();

        if ($user) {
            return $user;
        }

        // Partial match
        $user = User::where('name', 'LIKE', "%{$name}%")
            ->whereIn('role', ['customer', 'user'])
            ->first();

        if ($user) {
            return $user;
        }

        // Match individual words, useful for "Karl Nicko"
        $words = preg_split('/\s+/', $name);

        if (count($words) >= 2) {
            $query = User::query()
                ->whereIn('role', ['customer', 'user']);

            foreach ($words as $word) {
                if (strlen($word) >= 2) {
                    $query->where('name', 'LIKE', "%{$word}%");
                }
            }

            return $query->first();
        }

        return null;
    }

    /**
     * Format a single order.
     */
    private function formatOrder(Order $order): string
    {
        $status = strtoupper(
            str_replace('_', ' ', $order->order_status)
        );

        $completion = $order->estimated_completion
            ? $order->estimated_completion->format('M d, Y h:i A')
            : 'In Progress';

        $customerName = $order->customer
            ? $order->customer->name
            : 'Customer';

        $serviceName = $order->service
            ? $order->service->name
            : 'Laundry Service';

        return "Order #{$order->order_number}\n"
            . "Customer: {$customerName}\n"
            . "Status: {$status}\n"
            . "Service: {$serviceName}\n"
            . "Est. Completion: {$completion}\n"
            . "Total: P" . number_format($order->total_amount, 2);
    }

    /**
     * Build live database context for the LLM.
     */
    private function buildSystemPrompt(): string
    {
        $services = Service::where('status', 'active')
            ->get([
                'name',
                'price',
                'price_unit',
                'estimated_minutes',
            ]);

        $serviceList = $services
            ->map(
                fn ($s) =>
                "{$s->name}: P{$s->price}/{$s->price_unit} (~{$s->estimated_minutes} min)"
            )
            ->implode('; ');

        $machines = Machine::all([
            'machine_name',
            'machine_type',
            'status',
        ]);

        $washersIdle = $machines
            ->where('machine_type', 'washer')
            ->where('status', 'idle')
            ->count();

        $washersTotal = $machines
            ->where('machine_type', 'washer')
            ->count();

        $dryersIdle = $machines
            ->where('machine_type', 'dryer')
            ->where('status', 'idle')
            ->count();

        $dryersTotal = $machines
            ->where('machine_type', 'dryer')
            ->count();

        $todayOrders = Order::whereDate('created_at', today())->count();

        $pendingOrders = Order::where(
            'order_status',
            'pending'
        )->count();

        $processingOrders = Order::whereIn(
            'order_status',
            ['washing', 'rinsing', 'drying']
        )->count();

        $readyOrders = Order::where(
            'order_status',
            'ready_for_pickup'
        )->count();

        $completedToday = Order::where(
            'order_status',
            'completed'
        )
            ->whereDate('updated_at', today())
            ->count();

        $promos = Promotion::where('status', 'active')
            ->where('end_date', '>=', now())
            ->get([
                'name',
                'code',
                'discount_type',
                'discount_value',
            ]);

        $promoList = $promos->isEmpty()
            ? 'No active promotions right now.'
            : $promos
                ->map(
                    fn ($p) =>
                    "{$p->name} (Code: {$p->code}) — {$p->discount_value}"
                    . ($p->discount_type === 'percentage'
                        ? '%'
                        : ' pesos')
                    . ' off'
                )
                ->implode('; ');

        $lowStock = InventoryItem::whereColumn(
            'quantity',
            '<=',
            'minimum_stock'
        )
            ->where('status', 'active')
            ->pluck('name')
            ->implode(', ');

        $lowStockNote = $lowStock ?: 'All supplies are well-stocked.';

        /*
         * Do not expose the entire customer directory to the LLM.
         * It is unnecessary and creates a privacy risk.
         */
        return <<<PROMPT
You are STRICTLY the AI Assistant for Hour Wash Laundry Shop located in Magallanes St., Orosite, Legazpi City.

Store Hours: 7:00 AM – 6:00 PM Daily.

CRITICAL RULES:
- ONLY answer questions about laundry services, order tracking, machine status, store hours, promotions, and shop information.
- NEVER reveal passwords, tokens, secret keys, or authentication data.
- NEVER reveal private information belonging to other customers.
- NEVER reveal staff or administrator personal information.
- Do not invent order numbers, order statuses, prices, customers, or promotions.
- If a customer wants to track an order, ask for their order number, registered email, or account name.
- If the database does not contain the requested customer/order, clearly say it was not found.
- Always be friendly, helpful, and professional.
- Do not use emoji.
- Use readable dates such as "Aug 12, 2026 3:00 PM".

LIVE DATABASE CONTEXT:

SERVICES:
{$serviceList}

MACHINE STATUS:
Washers: {$washersIdle} available out of {$washersTotal} total
Dryers: {$dryersIdle} available out of {$dryersTotal} total

TODAY'S ORDER STATS:
Orders placed today: {$todayOrders}
Currently pending: {$pendingOrders}
In processing: {$processingOrders}
Ready for pickup: {$readyOrders}
Completed today: {$completedToday}

ACTIVE PROMOTIONS:
{$promoList}

INVENTORY:
Low stock items: {$lowStockNote}
PROMPT;
    }

    /**
     * Fallback smart engine.
     */
    private function getDomainReply(string $msg): string
    {
        // Order lookup is handled first.
        if ($this->isOrderRequest($msg)) {
            return $this->getOrderLookupReply($msg);
        }

        // Machine availability
        if (Str::contains($msg, [
            'machine',
            'washer',
            'dryer',
            'available',
            'slot',
        ])) {
            $machines = Machine::all([
                'machine_type',
                'status',
            ]);

            $washersIdle = $machines
                ->where('machine_type', 'washer')
                ->where('status', 'idle')
                ->count();

            $dryersIdle = $machines
                ->where('machine_type', 'dryer')
                ->where('status', 'idle')
                ->count();

            return "Machine Availability Right Now:\n"
                . "- Washers Available: {$washersIdle}\n"
                . "- Dryers Available: {$dryersIdle}\n\n"
                . "Visit us at Magallanes St., Orosite, Legazpi City!";
        }

        // Store hours
        if (Str::contains($msg, [
            'hour',
            'time',
            'open',
            'close',
            'schedule',
        ])) {
            return 'Hour Wash Laundry Shop is open daily from 7:00 AM to 6:00 PM at Magallanes St., Orosite, Legazpi City.';
        }

        // Location
        if (Str::contains($msg, [
            'location',
            'where',
            'address',
            'place',
            'legazpi',
            'orosite',
        ])) {
            return 'We are located at Magallanes St., Orosite, Legazpi City, Albay, Philippines.';
        }

        // Services
        if (Str::contains($msg, [
            'price',
            'rate',
            'cost',
            'fee',
            'package',
            'service',
            'wash',
            'dry',
        ])) {
            $services = Service::where('status', 'active')
                ->get([
                    'name',
                    'price',
                    'price_unit',
                ]);

            $servList = $services
                ->map(
                    fn ($s) =>
                    "- {$s->name}: P{$s->price}/{$s->price_unit}"
                )
                ->implode("\n");

            return "Our Active Laundry Services:\n"
                . "{$servList}\n\n"
                . "Visit our shop or book online!";
        }

        // Promotions
        if (Str::contains($msg, [
            'promo',
            'discount',
            'offer',
            'deal',
            'voucher',
        ])) {
            $promos = Promotion::where('status', 'active')
                ->where('end_date', '>=', now())
                ->get();

            if ($promos->isEmpty()) {
                return 'There are no active promotions right now, but check back soon!';
            }

            $promoList = $promos
                ->map(
                    fn ($p) =>
                    "- {$p->name} (Code: {$p->code}) — {$p->discount_value}"
                    . ($p->discount_type === 'percentage'
                        ? '%'
                        : ' pesos')
                    . ' off'
                )
                ->implode("\n");

            return "Active Promotions:\n"
                . "{$promoList}\n\n"
                . "Use the promo code when booking your order!";
        }

        // Greeting
        if (Str::contains($msg, [
            'hi',
            'hello',
            'hey',
            'good',
            'kumusta',
            'musta',
        ])) {
            return "Hello! Welcome to Hour Wash Laundry Shop!\n\n"
                . "I can help you with:\n"
                . "- Order tracking\n"
                . "- Machine availability\n"
                . "- Service rates and packages\n"
                . "- Active promotions\n"
                . "- Store hours\n\n"
                . "How can I assist you today?";
        }

        return "I am the HourWash Assistant dedicated to Hour Wash Laundry Shop. "
            . "I can help you track an order, check machine availability, "
            . "view services and rates, see active promotions, or check our "
            . "7:00 AM – 6:00 PM store hours.";
    }

    /**
     * Get customer's recent orders.
     */
    private function getCustomerOrderSummary(User $user): string
    {
        $orders = Order::with('service')
            ->where('customer_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        if ($orders->isEmpty()) {
            return "Hi {$user->name}! I found your account ({$user->email}), "
                . "but you don't have any laundry orders yet.";
        }

        $summary = "Orders for {$user->name}:\n\n";

        foreach ($orders as $order) {
            $status = strtoupper(
                str_replace('_', ' ', $order->order_status)
            );

            $date = $order->created_at
                ? $order->created_at->format('M d, Y h:i A')
                : 'Unknown';

            $completion = $order->estimated_completion
                ? $order->estimated_completion->format('M d, Y h:i A')
                : 'TBD';

            $serviceName = $order->service
                ? $order->service->name
                : 'Laundry Service';

            $summary .= "- #{$order->order_number} — {$status}\n";
            $summary .= "  Service: {$serviceName}\n";
            $summary .= "  Total: P" . number_format(
                $order->total_amount,
                2
            ) . "\n";
            $summary .= "  Ordered: {$date}\n";
            $summary .= "  Est. Completion: {$completion}\n\n";
        }

        return $summary
            . "Need more details on a specific order? "
            . "Just provide the order number.";
    }
}