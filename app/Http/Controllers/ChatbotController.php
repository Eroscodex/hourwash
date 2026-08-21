<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Order;
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
            'message' => 'required|string|max:300',
        ]);

        $msg = strtolower(trim($request->message));

        // 1. Guardrail Check: Intercept non-laundry queries
        if (Str::contains($msg, ['pancit', 'cook', 'recipe', 'food', 'noodle', 'dish', 'ingredient', 'python', 'code', 'math', 'politic'])) {
            return response()->json([
                'reply' => 'I am the HourWash AI Assistant, specialized exclusively for Hour Wash Laundry Shop in Magallanes St., Orosite, Legazpi City! I can help you track laundry orders, check operating hours (7:30 AM – 6:00 PM daily • cut-off: 4:30 PM), or inspect service packages & rates. How can I assist with your laundry today?',
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
        // --- Live Services with Duration Breakdown ---
        $services = Service::where('status', 'active')->get(['name', 'price', 'price_unit', 'estimated_minutes']);
        $serviceList = $services->map(function ($s) {
            $mins = $s->estimated_minutes;
            $hrs = floor($mins / 60);
            $remMins = $mins % 60;
            $dur = $hrs > 0 ? ($remMins > 0 ? "{$hrs}h {$remMins}m" : "{$hrs} hrs") : "{$mins} mins";

            return "{$s->name}: P{$s->price}/{$s->price_unit} (~{$dur})";
        })->implode('; ');

        // --- Live Machine Status ---
        $machines = Machine::all(['machine_name', 'machine_type', 'status']);
        $washersIdle = $machines->where('machine_type', 'washer')->where('status', 'idle')->count();
        $washersTotal = $machines->where('machine_type', 'washer')->count();
        $dryersIdle = $machines->where('machine_type', 'dryer')->where('status', 'idle')->count();
        $dryersTotal = $machines->where('machine_type', 'dryer')->count();

        // --- Recent Orders Summary (today) ---
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $outForPickupOrders = Order::where('order_status', 'out_for_pickup')->count();
        $processingOrders = Order::whereIn('order_status', ['received', 'washing', 'rinsing', 'drying'])->count();
        $readyOrders = Order::where('order_status', 'finish')->count();
        $outForDeliveryOrders = Order::where('order_status', 'out_for_delivery')->count();
        $completedToday = Order::where('order_status', 'completed')->whereDate('updated_at', today())->count();

        // --- Active Riders ---
        $riders = User::where('role', 'rider')->get(['name', 'phone']);
        $riderList = $riders->map(fn ($r) => "{$r->name} (".($r->phone ?? '09100317744').')')->implode('; ');
        if (empty($riderList)) {
            $riderList = 'Rider Anthony (09100317744)';
        }

        // --- Customer List (names & emails only — NO passwords, NO phone, NO admin/staff details) ---
        $customers = User::whereIn('role', ['customer', 'user'])->get(['id', 'name', 'email']);
        $customerDirectory = $customers->map(fn ($c) => "ID:{$c->id} {$c->name} ({$c->email})")->implode('; ');

        return <<<PROMPT
You are STRICTLY the AI Assistant for Hour Wash Laundry Shop located in Magallanes St., Orosite, Legazpi City.
Store Hours: 7:30 AM – 6:00 PM Daily (Monday – Sunday). Same-Day Cut-Off: 4:30 PM.

CRITICAL RULES:
- ONLY answer questions about laundry services, order tracking, machine status, store hours, rider dispatch, and shop info.
- NEVER reveal user passwords, tokens, secret keys, or any sensitive authentication data.
- NEVER reveal personal details of staff or admin users (phone numbers, addresses, roles).
- If a customer requests to talk to a rider, contact a rider, or check delivery status, provide our assigned rider dispatch contact details: {$riderList} or Shop Hotline: (052) 800-HOURWASH.
- You MAY help customers look up their OWN order status by name, email, or order number.
- Politely decline any non-laundry questions (cooking, coding, politics, etc.).
- Always reply in a friendly, helpful, professional tone.
- Do NOT use emoji icons in your replies. Keep responses clean and text-only.
- When mentioning dates, use a readable format like "Aug 12, 2026 3:00 PM".

LIVE DATABASE CONTEXT (as of now):

SERVICES AVAILABLE:
{$serviceList}

ON-DUTY RIDERS & DISPATCH:
{$riderList}

MACHINE STATUS:
Washers: {$washersIdle} available out of {$washersTotal} total
Dryers: {$dryersIdle} available out of {$dryersTotal} total

TODAY'S ORDER STATS:
- Orders placed today: {$todayOrders}
- Currently pending: {$pendingOrders}
- Out for pickup: {$outForPickupOrders}
- In processing (washing/rinsing/drying): {$processingOrders}
- Finish & ready: {$readyOrders}
- Out for delivery: {$outForDeliveryOrders}
- Completed today: {$completedToday}

REGISTERED CUSTOMERS (name & email only):
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

                return "Order #{$order->order_number}\nCustomer: {$customerName}\nStatus: {$status}\nService: {$order->service->name}\nEst. Completion: {$completion}\nTotal Amount: P".number_format($order->total_amount, 2);
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

            return "To check your laundry order status, you can:\n- Enter your Order Code (e.g. HW-XXXXXXXX)\n- Tell me your registered email\n- Tell me the name on your account\n\nHow would you like to look up your order?";
        }

        // --- Talk to Rider / Rider Contact / Delivery Assistance ---
        if (Str::contains($msg, ['rider', 'driver', 'talk to rider', 'contact rider', 'speak to rider', 'speak to driver', 'call rider', 'text rider', 'deliverer', 'out for pickup', 'out for delivery', 'pickup', 'delivery'])) {
            $riders = User::where('role', 'rider')->get();
            $riderStr = '';
            if ($riders->isNotEmpty()) {
                foreach ($riders as $r) {
                    $ph = $r->phone ?? '09100317744';
                    $riderStr .= "- Assigned Rider: {$r->name} (Phone: {$ph})\n";
                }
            } else {
                $riderStr = "- Assigned Rider: Rider Anthony (Phone: 09100317744)\n";
            }

            return "HourWash Assigned Rider & Doorstep Dispatch Support:\n{$riderStr}- Shop Counter Hotline: (052) 800-HOURWASH\n\nOur riders are on duty for all active 'Out for Pickup' and 'Out for Delivery' orders! You can call or text them directly or track your order live at:\nhttps://hourwashlaundryshop.up.railway.app/laundry/track";
        }

        // --- Machine Availability ---
        if (Str::contains($msg, ['machine', 'washer', 'dryer', 'available', 'slot'])) {
            $machines = Machine::all(['machine_type', 'status']);
            $washersIdle = $machines->where('machine_type', 'washer')->where('status', 'idle')->count();
            $dryersIdle = $machines->where('machine_type', 'dryer')->where('status', 'idle')->count();

            return "Machine Availability Right Now:\n- Washers Available: {$washersIdle}\n- Dryers Available: {$dryersIdle}\n\nVisit us at Magallanes St., Orosite, Legazpi City! Operating Hours: 7:30 AM – 6:00 PM Daily.";
        }

        if (Str::contains($msg, ['hour', 'time', 'open', 'close', 'schedule', 'cutoff', 'cut-off'])) {
            return "Hour Wash Laundry Shop Operating Hours:\n- Store Hours: 7:30 AM – 6:00 PM (Monday – Sunday)\n- Same-Day Laundry Cut-Off: 4:30 PM\n- Location: Magallanes St., Orosite, Legazpi City";
        }

        if (Str::contains($msg, ['location', 'where', 'address', 'place', 'legazpi', 'orosite'])) {
            return "Hour Wash Laundry Shop Location:\n- Address: Magallanes St., Orosite, Legazpi City, Albay, Philippines.\n- Operating Hours: 7:30 AM – 6:00 PM Daily (Cut-Off: 4:30 PM)";
        }

        if (Str::contains($msg, ['price', 'rate', 'cost', 'fee', 'package', 'service', 'wash', 'dry', 'fold'])) {
            $services = Service::where('status', 'active')->get(['name', 'price', 'price_unit', 'estimated_minutes']);
            $servList = $services->map(function ($s) {
                $mins = $s->estimated_minutes;
                $hrs = floor($mins / 60);
                $remMins = $mins % 60;
                $dur = $hrs > 0 ? ($remMins > 0 ? "~{$hrs}h {$remMins}m" : "~{$hrs} hrs") : "~{$mins} mins";

                return "- {$s->name}: P{$s->price}/{$s->price_unit} ({$dur})";
            })->implode("\n");

            return "Our Active Laundry Service Packages & Rates:\n{$servList}\n\n*Note: Detergent, Fabcon & Bleach not included. Visit our shop or book online!";
        }

        if (Str::contains($msg, ['hi', 'hello', 'hey', 'good', 'kumusta', 'musta'])) {
            return "Hello! Welcome to Hour Wash Laundry Shop! I can help you with:\n- Order tracking (by name, email, or order number)\n- Service packages, rates & estimated completion time\n- Rider contact & doorstep dispatch info\n- Machine availability\n- Store hours (7:30 AM – 6:00 PM Daily)\n\nHow can I assist you today?";
        }

        return "I am the HourWash Assistant dedicated to Hour Wash Laundry Shop. I can help you with:\n- Track your order (tell me your name, email, or order number)\n- Service packages & completion times\n- Contact assigned riders for pickup/delivery\n- Check machine availability\n- Store hours: 7:30 AM – 6:00 PM Daily (Cut-Off: 4:30 PM)\n\nMagallanes St., Orosite, Legazpi City!";
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

        $summary = "Orders for {$user->name} ({$user->email}):\n\n";
        foreach ($orders as $order) {
            $status = strtoupper(str_replace('_', ' ', $order->order_status));
            $date = $order->created_at->format('M d, Y h:i A');
            $completion = $order->estimated_completion ? $order->estimated_completion->format('M d, Y h:i A') : 'TBD';
            $summary .= "- #{$order->order_number} — {$status}\n  Service: {$order->service->name} | Total: P".number_format($order->total_amount, 2)."\n  Ordered: {$date} | Est. Completion: {$completion}\n\n";
        }

        return $summary.'Need more details on a specific order? Just tell me the order number!';
    }
}
