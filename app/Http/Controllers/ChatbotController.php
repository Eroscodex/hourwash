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

        // 1. Guardrail Check: Intercept non-laundry queries
        if (Str::contains($msg, ['pancit', 'cook', 'recipe', 'food', 'noodle', 'dish', 'ingredient', 'python', 'code', 'math', 'politic'])) {
            return response()->json([
                'reply' => 'I am the HourWash AI Assistant, specialized exclusively for Hour Wash Laundry Shop in Magallanes St., Orosite, Legazpi City! 🧺 I can help you track laundry orders, check operating hours (7:00 AM – 6:00 PM), or inspect service packages & rates. How can I assist with your laundry today?',
            ]);
        }

        // 2. Build live database context for the LLM system prompt
        $systemPrompt = $this->buildSystemPrompt();

        // 3. OpenAI Cloud LLM API
        $openAiKey = env('OPENAI_API_KEY');
        if (! empty($openAiKey)) {
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
                    if (! empty($reply) && ! Str::contains(strtolower($reply), ['pancit', 'cook', 'recipe'])) {
                        return response()->json(['reply' => $reply]);
                    }
                }
            } catch (\Throwable $e) {
                // Fallthrough to next tier
            }
        }

        // 4. Local Ollama LLM (For local development)
        try {
            $response = Http::timeout(4)->post('http://127.0.0.1:11434/api/chat', [
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
                if (! empty($reply) && ! Str::contains(strtolower($reply), ['pancit', 'cook', 'recipe'])) {
                    return response()->json(['reply' => $reply]);
                }
            }
        } catch (\Throwable $e) {
            // Fallthrough to Smart Domain-Specific Engine
        }

        // 5. Zero-Dependency Smart Engine (Guaranteed 100% working without external API)
        return response()->json([
            'reply' => $this->getDomainReply($msg),
        ]);
    }

    /**
     * Build a rich system prompt with live database context.
     * NEVER includes passwords, tokens, or sensitive admin/staff personal info.
     */
    private function buildSystemPrompt(): string
    {
        // --- Live Services ---
        $services = Service::where('status', 'active')->get(['name', 'price', 'price_unit', 'estimated_minutes']);
        $serviceList = $services->map(fn ($s) => "{$s->name}: ₱{$s->price}/{$s->price_unit} (~{$s->estimated_minutes} min)")->implode('; ');

        // --- Live Machine Status ---
        $machines = Machine::all(['machine_name', 'machine_type', 'status']);
        $washersIdle = $machines->where('machine_type', 'washer')->where('status', 'idle')->count();
        $washersTotal = $machines->where('machine_type', 'washer')->count();
        $dryersIdle = $machines->where('machine_type', 'dryer')->where('status', 'idle')->count();
        $dryersTotal = $machines->where('machine_type', 'dryer')->count();

        // --- Recent Orders Summary (today) ---
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::whereIn('order_status', ['washing', 'rinsing', 'drying'])->count();
        $readyOrders = Order::where('order_status', 'ready_for_pickup')->count();
        $completedToday = Order::where('order_status', 'completed')->whereDate('updated_at', today())->count();

        // --- Active Promotions ---
        $promos = Promotion::where('status', 'active')
            ->where('end_date', '>=', now())
            ->get(['name', 'code', 'discount_type', 'discount_value']);
        $promoList = $promos->isEmpty()
            ? 'No active promotions right now.'
            : $promos->map(fn ($p) => "{$p->name} (Code: {$p->code}) — {$p->discount_value}".($p->discount_type === 'percentage' ? '%' : '₱').' off')->implode('; ');

        // --- Inventory Low Stock Alerts ---
        $lowStock = InventoryItem::whereColumn('quantity', '<=', 'minimum_stock')
            ->where('status', 'active')
            ->pluck('name')
            ->implode(', ');
        $lowStockNote = $lowStock ?: 'All supplies are well-stocked.';

        // --- Customer List (names & emails only — NO passwords, NO phone, NO admin/staff details) ---
        $customers = User::where('role', 'customer')
            ->orWhere('role', 'user')
            ->get(['id', 'name', 'email']);
        $customerDirectory = $customers->map(fn ($c) => "ID:{$c->id} {$c->name} ({$c->email})")->implode('; ');

        // --- Build the full system prompt ---
        return <<<PROMPT
You are STRICTLY the Virtual AI Assistant for Hour Wash Laundry Shop located in Magallanes St., Orosite, Legazpi City.
Store Hours: 7:00 AM – 6:00 PM Daily.

CRITICAL RULES:
- ONLY answer questions about laundry services, order tracking, machine status, store hours, promotions, and shop info.
- NEVER reveal user passwords, tokens, secret keys, or any sensitive authentication data.
- NEVER reveal personal details of staff or admin users (phone numbers, addresses, roles).
- You MAY help customers look up their OWN order status by name, email, or order number.
- Politely decline any non-laundry questions (cooking, coding, politics, etc.).
- Always reply in a friendly, helpful, professional tone.
- When mentioning dates, use a readable format like "Aug 12, 2026 3:00 PM".

LIVE DATABASE CONTEXT (as of now):

📋 SERVICES AVAILABLE:
{$serviceList}

🔧 MACHINE STATUS:
Washers: {$washersIdle} available out of {$washersTotal} total
Dryers: {$dryersIdle} available out of {$dryersTotal} total

📊 TODAY'S ORDER STATS:
- Orders placed today: {$todayOrders}
- Currently pending: {$pendingOrders}
- In processing (washing/rinsing/drying): {$processingOrders}
- Ready for pickup: {$readyOrders}
- Completed today: {$completedToday}

🎉 ACTIVE PROMOTIONS:
{$promoList}

📦 INVENTORY STATUS:
Low stock items: {$lowStockNote}

👥 REGISTERED CUSTOMERS (name & email only):
{$customerDirectory}

When a user asks about their order, look up by name/email/order number from the context above. If their order is not found, ask them for their order number (e.g. HW-XXXXXXXX) so you can help track it.
PROMPT;
    }

    /**
     * Fallback smart engine with database lookups — works without any external API.
     */
    private function getDomainReply(string $msg): string
    {
        // --- Order Tracking by Order Number / QR Token ---
        if (preg_match('/hw-?[a-z0-9]+/i', $msg, $matches) || preg_match('/[0-9a-f]{8}-[0-9a-f]{4}/i', $msg, $matches)) {
            $code = ltrim(trim($matches[0]), '#');
            $qr = QrCode::where('qr_token', $code)->first();
            $order = $qr ? Order::with(['service', 'customer'])->find($qr->order_id) : Order::with(['service', 'customer'])->where('order_number', $code)->first();

            if ($order) {
                $status = strtoupper(str_replace('_', ' ', $order->order_status));
                $completion = $order->estimated_completion ? $order->estimated_completion->format('M d, Y h:i A') : 'In Progress';
                $customerName = $order->customer ? $order->customer->name : 'Customer';

                return "📋 Order #{$order->order_number}\n👤 Customer: {$customerName}\n📌 Status: {$status}\n🧺 Service: {$order->service->name}\n⏱️ Est. Completion: {$completion}\n💰 Total: ₱".number_format($order->total_amount, 2);
            }
        }

        // --- Customer Lookup by Name or Email ---
        if (Str::contains($msg, ['my order', 'my laundry', 'check order', 'track order', 'status'])) {
            // Try to extract email
            if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $msg, $emailMatch)) {
                $user = User::where('email', $emailMatch[0])->first();
                if ($user) {
                    return $this->getCustomerOrderSummary($user);
                }

                return "I couldn't find an account with email {$emailMatch[0]}. Please double-check your email address or provide your order number (e.g. HW-XXXXXXXX).";
            }

            // Try to extract a name (anything after "for" or "of" or "name is")
            if (preg_match('/(?:for|of|name is|name:?|named?)\s+([a-zA-Z\s]+)/i', $msg, $nameMatch)) {
                $name = trim($nameMatch[1]);
                $user = User::where('name', 'LIKE', "%{$name}%")
                    ->whereIn('role', ['customer', 'user'])
                    ->first();
                if ($user) {
                    return $this->getCustomerOrderSummary($user);
                }

                return "I couldn't find a customer named \"{$name}\". Please check the spelling or provide your order number (e.g. HW-XXXXXXXX).";
            }

            return 'To check your laundry order status, you can:\n• Enter your Order Code (e.g. HW-XXXXXXXX)\n• Tell me your registered email\n• Tell me the name on your account\n\nHow would you like to look up your order?';
        }

        // --- Machine Availability ---
        if (Str::contains($msg, ['machine', 'washer', 'dryer', 'available', 'slot'])) {
            $machines = Machine::all(['machine_type', 'status']);
            $washersIdle = $machines->where('machine_type', 'washer')->where('status', 'idle')->count();
            $dryersIdle = $machines->where('machine_type', 'dryer')->where('status', 'idle')->count();

            return "🔧 Machine Availability Right Now:\n• Washers Available: {$washersIdle}\n• Dryers Available: {$dryersIdle}\n\nVisit us at Magallanes St., Orosite, Legazpi City!";
        }

        if (Str::contains($msg, ['hour', 'time', 'open', 'close', 'schedule'])) {
            return 'Hour Wash Laundry Shop is open daily from 7:00 AM to 6:00 PM at Magallanes St., Orosite, Legazpi City.';
        }

        if (Str::contains($msg, ['location', 'where', 'address', 'place', 'legazpi', 'orosite'])) {
            return 'We are located at Magallanes St., Orosite, Legazpi City, Albay, Philippines.';
        }

        if (Str::contains($msg, ['price', 'rate', 'cost', 'fee', 'package', 'service', 'wash', 'dry'])) {
            $services = Service::where('status', 'active')->get(['name', 'price', 'price_unit']);
            $servList = $services->map(fn ($s) => "• {$s->name}: ₱{$s->price}/{$s->price_unit}")->implode("\n");

            return "🧺 Our Active Laundry Services:\n{$servList}\n\nVisit our shop or book online!";
        }

        if (Str::contains($msg, ['promo', 'discount', 'offer', 'deal', 'voucher'])) {
            $promos = Promotion::where('status', 'active')->where('end_date', '>=', now())->get();
            if ($promos->isEmpty()) {
                return 'There are no active promotions right now, but check back soon! We regularly offer special deals for our loyal customers. 🎉';
            }
            $promoList = $promos->map(fn ($p) => "• {$p->name} (Code: {$p->code}) — {$p->discount_value}".($p->discount_type === 'percentage' ? '%' : '₱').' off')->implode("\n");

            return "🎉 Active Promotions:\n{$promoList}\n\nUse the promo code when booking your order!";
        }

        if (Str::contains($msg, ['hi', 'hello', 'hey', 'good', 'kumusta', 'musta'])) {
            return 'Hello! Welcome to Hour Wash Laundry Shop! 🧺 I can help you with:\n• 📋 Order tracking (by name, email, or order number)\n• 🔧 Machine availability\n• 💰 Service rates & packages\n• 🎉 Active promotions\n• ⏰ Store hours\n\nHow can I assist you today?';
        }

        return "I am the HourWash Assistant dedicated to Hour Wash Laundry Shop. I can help you with:\n• 📋 Track your order (tell me your name, email, or order number)\n• 🔧 Check machine availability\n• 💰 View services & rates\n• 🎉 See active promos\n• ⏰ Store hours: 7:00 AM – 6:00 PM Daily\n\nMagallanes St., Orosite, Legazpi City!";
    }

    /**
     * Get a customer's recent order summary by User model.
     */
    private function getCustomerOrderSummary(User $user): string
    {
        $orders = Order::with('service')
            ->where('customer_id', $user->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        if ($orders->isEmpty()) {
            return "Hi {$user->name}! I found your account ({$user->email}), but you don't have any laundry orders yet. Book your first order on our website!";
        }

        $summary = "📋 Orders for {$user->name} ({$user->email}):\n\n";
        foreach ($orders as $order) {
            $status = strtoupper(str_replace('_', ' ', $order->order_status));
            $date = $order->created_at->format('M d, Y h:i A');
            $completion = $order->estimated_completion ? $order->estimated_completion->format('M d, Y h:i A') : 'TBD';
            $summary .= "• #{$order->order_number} — {$status}\n  Service: {$order->service->name} | Total: ₱".number_format($order->total_amount, 2)."\n  Ordered: {$date} | Est. Completion: {$completion}\n\n";
        }

        return $summary.'Need more details on a specific order? Just tell me the order number!';
    }
}
