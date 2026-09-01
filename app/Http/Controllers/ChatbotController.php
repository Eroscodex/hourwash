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
        /** @var User|null $authUser */
        $authUser = auth()->user();

        // 1. Guardrail Check: Intercept non-laundry queries
        if (Str::contains($msg, ['pancit', 'cook', 'recipe', 'food', 'noodle', 'dish', 'ingredient', 'python', 'code', 'math', 'politic'])) {
            return response()->json([
                'reply' => 'I am the HourWash AI Assistant, specialized exclusively for Hour Wash Laundry Shop in Magallanes St., Orosite, Legazpi City! I can help you track laundry orders, check operating hours (7:30 AM – 6:00 PM daily • cut-off: 4:30 PM), or inspect service packages & rates. How can I assist with your laundry today?',
            ]);
        }

        // 2. Build live database context tailored to the user's role/context
        $systemPrompt = $this->buildSystemPrompt($authUser);

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
            'reply' => $this->getDomainReply($msg, $authUser),
        ]);
    }

    /**
     * Build a role-tailored system prompt with live context and Philippine language support.
     */
    private function buildSystemPrompt(?User $user): string
    {
        $role = $user ? $user->role : 'guest';
        $userName = $user ? $user->name : 'Visitor';

        $services = Service::where('status', 'active')->get(['name', 'price', 'price_unit', 'estimated_minutes']);
        $serviceList = $services->map(function ($s) {
            $mins = $s->estimated_minutes;
            $hrs = floor($mins / 60);
            $remMins = $mins % 60;
            $dur = $hrs > 0 ? ($remMins > 0 ? "{$hrs}h {$remMins}m" : "{$hrs} hrs") : "{$mins} mins";

            return "• {$s->name}: P{$s->price}/{$s->price_unit} (~{$dur})";
        })->implode("\n");

        $langInstructions = <<<'LANG'
PHILIPPINE LANGUAGES & DIALECTS SUPPORT:
- You must fluently understand and respond in ANY Philippine language or dialect, including:
  1. English & Taglish (e.g. "How much is wash and fold?", "Magkano pa wash?")
  2. Tagalog / Filipino (e.g. "Magkano po magpalaba?", "Saan po ang store nyo?", "Kailan matatapos ang labada ko?")
  3. Bicolano / Bikol (Legazpi/Albay/Naga - e.g. "Gurano ang palaba?", "Hain ang tindahan nindo?", "Saino tabi ang pwesto nindo?", "Nuarin matatapos ang labada ko?", "Marhay na aldaw", "Dios marhay na hapon")
  4. Bisaya / Cebuano / Hiligaynon (e.g. "Pila ang palaba?", "Asa man inyong shop?", "Kanus-a mahuman ang labada ko?")
  5. Other PH Dialects (Waray, Ilocano, Kapampangan, Pangasinan, Chavacano).
- CRITICAL: ALWAYS reply in the SAME language or dialect used by the user! If asked in Bicolano, respond in friendly Bicolano! If asked in Tagalog, respond in Tagalog! If asked in Bisaya, respond in Bisaya!
LANG;

        if ($role === 'guest') {
            return <<<PROMPT
You are the Public Storefront AI Assistant for Hour Wash Laundry Shop located in Magallanes St., Orosite, Legazpi City, Albay.
Your goal is to assist visitors on our Public Welcome Page.

{$langInstructions}

STOREFRONT INFORMATION & PAGES AVAILABLE:
- Home: Store Overview, Address (Magallanes St., Orosite, Legazpi City), Store Hours (7:30 AM – 6:00 PM Daily, Cut-Off 4:30 PM), Contact Details.
- Services & Rates:
{$serviceList}
- How It Works: 5-step process (Place Order -> Drop-off or Pickup & Delivery -> Wash/Dry/Fold -> Live QR Tracking -> Doorstep Delivery or Shop Pickup).
- Track Order: Look up active orders by Order Code (e.g. #HW-XXXXXX) or registered email.
- Customer Reviews: Customer ratings, satisfaction, and feedback policies.
- About Us: Hour Wash Laundry Management System background and technology.
- Developers: Built by Eroscodex Team.
- Privacy Policy & Terms: Cash on Delivery (COD) payments, privacy protections, standard turnaround times.

CRITICAL SCOPING RULES:
- ONLY answer questions regarding the Welcome Page content: Home, Services & Rates, How It Works, Track Order, Customer Reviews, About Us, Developers, Privacy Policy, and Terms & Conditions.
- DO NOT reveal internal rider dispatch tasks, admin revenues, or staff machine override logs to public visitors.
- Keep responses friendly, professional, clear, and text-only (no emojis).
PROMPT;
        }

        if ($role === 'customer') {
            $myOrders = $this->getCustomerOrderSummary($user);

            return <<<PROMPT
You are the Customer Portal AI Assistant for Hour Wash Laundry Shop, assisting logged-in customer {$userName}.

{$langInstructions}

CUSTOMER ORDERS:
{$myOrders}

SERVICES AVAILABLE:
{$serviceList}

SCOPE:
- Assist customer {$userName} with their active orders, tracking status, package pricing, online booking, profile updates, and submitting reviews.
- Keep responses text-only, friendly, and helpful in the user's preferred Philippine language.
PROMPT;
        }

        if ($role === 'staff') {
            $machines = Machine::all(['machine_name', 'machine_type', 'status']);
            $washersIdle = $machines->where('machine_type', 'washer')->where('status', 'idle')->count();
            $dryersIdle = $machines->where('machine_type', 'dryer')->where('status', 'idle')->count();
            $inShopCount = Order::whereIn('order_status', ['received', 'washing', 'rinsing', 'drying', 'finish'])->count();

            return <<<PROMPT
You are the Staff Operations AI Assistant for Hour Wash Laundry Shop, assisting staff member {$userName}.

{$langInstructions}

IN-SHOP STATUS:
- Washers Idle: {$washersIdle} / 5
- Dryers Idle: {$dryersIdle} / 5
- Active In-Shop Orders: {$inShopCount}

SCOPE:
- Assist staff with in-shop machine statuses, order stage updates (received -> washing -> rinsing -> drying -> finish), and brownout extensions.
PROMPT;
        }

        if ($role === 'admin' || $role === 'owner') {
            $totalOrders = Order::count();
            $todayPaidRevenue = Order::where('payment_status', 'paid')->whereDate('updated_at', today())->sum('total_amount');
            $totalUsers = User::count();

            return <<<PROMPT
You are the Admin Management AI Assistant for Hour Wash Laundry Shop, assisting {$userName} (Role: {$user->role}).

{$langInstructions}

ADMIN STATS:
- Lifetime Orders: {$totalOrders}
- Today's Paid Revenue: P{$todayPaidRevenue}
- Total Accounts: {$totalUsers}

SCOPE:
- Assist admin/owner with store analytics, total revenue, inventory stock, user accounts, SMS/Email logs, and machine configuration.
PROMPT;
        }

        return 'You are the Hour Wash AI Assistant for Hour Wash Laundry Shop.';
    }

    /**
     * Fallback smart domain engine with multi-lingual Philippine dialect support.
     */
    private function getDomainReply(string $msg, ?User $user): string
    {
        $role = $user ? $user->role : 'guest';

        // 1. Order Tracking by Order Number / QR Token (Available to all)
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

            return "I couldn't find an order with code \"{$code}\". Please check your receipt or order history and try again!";
        }

        // 2. WELCOME PAGE / PUBLIC STOREFRONT SCOPING (For Visitors & Guest Chatbot)
        if ($role === 'guest') {
            // How It Works / Process (EN / Tagalog / Bicolano / Bisaya)
            if (Str::contains($msg, ['how it works', 'how to order', 'process', 'steps', 'workflow', 'how does it work', 'paano', 'paoano', 'hakbang', 'proseso', 'giunsa', 'unsaon'])) {
                return "How Hour Wash Laundry Shop Works / Paano Magpalaba:\n1. Select Service Package (Wash Only P75, Dry Only P75, Fold Only P50, Self-Service P150, or Full Service with Pickup & Delivery P250)\n2. Drop Off or Pickup: Drop off at shop or our rider collects from your doorstep\n3. Cleaning Cycle: Professional Wash, Rinse, Dry & Fold\n4. Live QR Tracking: Track status on your phone via Order # (e.g. #HW-XXXXXX) or QR Tag\n5. Receipt & Delivery: Claim at shop or doorstep delivery!";
            }

            // Customer Reviews & Ratings
            if (Str::contains($msg, ['review', 'reviews', 'rating', 'ratings', 'feedback', 'testimonial', 'repaso', 'komento', 'nindot'])) {
                return "Customer Reviews & Quality Assurance:\nHour Wash Laundry Shop prides itself on fast, clean, and reliable service in Legazpi City! Logged-in customers can submit star ratings and feedback directly on their dashboard after completing an order.";
            }

            // About Us
            if (Str::contains($msg, ['about us', 'about', 'background', 'shop info', 'system info', 'tungkol', 'manungod'])) {
                return "About Hour Wash Laundry Shop:\nWe are Legazpi City's premier laundry management system located in Magallanes St., Orosite. We offer fast, hygienic, and affordable wash, dry, fold, and doorstep pickup & delivery services.";
            }

            // Developers
            if (Str::contains($msg, ['developer', 'developers', 'creator', 'built', 'team', 'who made', 'gumawa', 'kagsadiri'])) {
                return "Hour Wash System Developers:\nDeveloped by Eroscodex Team using Laravel 11, Tailwind CSS, PHP 8.5, and Vite asset bundling.";
            }

            // Privacy Policy
            if (Str::contains($msg, ['privacy', 'privacy policy', 'security', 'data protection', 'patakaran'])) {
                return "Privacy Policy Summary:\nHour Wash respects your privacy. All customer addresses, phone numbers, and order histories are kept strictly confidential and protected.";
            }

            // Terms & Conditions
            if (Str::contains($msg, ['terms', 'condition', 'terms and conditions', 'policy', 'payment method', 'cod', 'singil'])) {
                return "Terms & Conditions Summary:\n- Payment: Cash on Delivery (COD) or Cash at Shop Counter.\n- Turnaround: Same-day turnaround for orders submitted before 4:30 PM cut-off.\n- Laundry Policy: Please inspect items and check pockets prior to handover.";
            }

            // Intercept internal staff/rider/admin questions when on public storefront
            if (Str::contains($msg, ['rider task', 'rider list', 'dispatch list', 'admin revenue', 'staff override', 'inventory stock', 'sms log'])) {
                return "I am the HourWash Public Storefront Assistant! I focus on helping our store visitors with:\n- Home & Store Info (Magallanes St., Orosite, Legazpi City • 7:30 AM – 6:00 PM)\n- Services & Rates (Wash Only P75, Dry P75, Fold P50, Self-Service P150, Full Service P200/P250)\n- How It Works (Ordering & Pickup/Delivery)\n- Track Order (#HW-XXXXXX)\n- Customer Reviews, About Us, Developers, Privacy Policy & Terms\n\nHow can I help you today?";
            }
        }

        // 3. Customer Order Lookup / Timing (EN / Tagalog / Bicolano / Bisaya)
        if (Str::contains($msg, ['my order', 'my laundry', 'check order', 'track order', 'status', 'kailan matatapos', 'nuarin matatapos', 'kanus-a mahuman', 'hain na', 'nasaan na', 'asa na'])) {
            if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $msg, $emailMatch)) {
                $foundUser = User::where('email', $emailMatch[0])->first();
                if ($foundUser) {
                    return $this->getCustomerOrderSummary($foundUser);
                }

                return "I couldn't find an account with email {$emailMatch[0]}. Please double-check your email or provide your order code (e.g. #HW-XXXXXX).";
            }

            if ($user && $user->isCustomer()) {
                return $this->getCustomerOrderSummary($user);
            }

            return 'To track your laundry order / para malaman ang status, please tell me your Order Code (e.g. #HW-XXXXXX) or your registered email address!';
        }

        // 4. Services & Rates / Pricing (EN / Tagalog / Bicolano: Gurano / Bisaya: Pila)
        if (Str::contains($msg, ['price', 'rate', 'cost', 'fee', 'package', 'service', 'wash', 'dry', 'fold', 'magkano', 'gurano', 'pila', 'bayad', 'presyo', 'singil', 'palaba', 'laba'])) {
            $services = Service::where('status', 'active')->get(['name', 'price', 'price_unit', 'estimated_minutes']);
            $servList = $services->map(function ($s) {
                $mins = $s->estimated_minutes;
                $hrs = floor($mins / 60);
                $remMins = $mins % 60;
                $dur = $hrs > 0 ? ($remMins > 0 ? "~{$hrs}h {$remMins}m" : "~{$hrs} hrs") : "~{$mins} mins";

                return "• {$s->name}: P{$s->price}/{$s->price_unit} ({$dur})";
            })->implode("\n");

            return "Our Active Laundry Service Packages & Rates / Mga Presyo sa Palaba:\n{$servList}\n\nLocation: Magallanes St., Orosite, Legazpi City! Book online or visit our store.";
        }

        // 5. Store Hours & Location (EN / Tagalog / Bicolano: Hain / Bisaya: Asa)
        if (Str::contains($msg, ['hour', 'time', 'open', 'close', 'schedule', 'cutoff', 'cut-off', 'location', 'where', 'address', 'legazpi', 'orosite', 'oras', 'bukas', 'sara', 'saan', 'hain', 'saino', 'saen', 'pwesto', 'lugar', 'asa', 'dapit', 'abli', 'sirado'])) {
            return "Hour Wash Laundry Shop Details / Pwesto at Oras:\n- Address: Magallanes St., Orosite, Legazpi City, Albay, Philippines.\n- Operating Hours: 7:30 AM – 6:00 PM Daily (Monday – Sunday)\n- Same-Day Cut-Off: 4:30 PM\n- Hotline: (052) 800-HOURWASH";
        }

        // 6. Greetings (EN / Tagalog: Kumusta / Bicolano: Marhay / Bisaya: Maayong)
        if (Str::contains($msg, ['hi', 'hello', 'hey', 'good', 'kumusta', 'musta', 'marhay', 'dios marhay', 'maayong'])) {
            if ($role === 'guest') {
                return "Marhay na aldaw / Hello! Welcome to Hour Wash Laundry Shop! I can assist you with:\n- Services & Rates / Presyo (Wash, Dry, Fold, Self-Service, Pickup & Delivery)\n- How It Works / Paano magpalaba\n- Track Order (by Order # or Email)\n- Customer Reviews, About Us, Developers, Privacy Policy & Terms\n\nHow can I help you today, po?";
            }

            return "Hello {$user->name}! Welcome back to Hour Wash Laundry Portal! How can I assist you with your dashboard today?";
        }

        // 7. General Storefront Fallback
        return "Hour Wash Laundry Shop AI Assistant:\n- Location: Magallanes St., Orosite, Legazpi City\n- Store Hours: 7:30 AM – 6:00 PM Daily (Cut-Off: 4:30 PM)\n- Services & Rates: Wash Only (P75), Dry Only (P75), Fold Only (P50), Self-Service (P150), Full-Service (P200/P250)\n- Track Order: Provide your Order Code (e.g. #HW-XXXXXX) to view live status!";
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
            return "Hi {$user->name}! You don't have any active laundry orders yet. Book your first order on our website!";
        }

        $summary = "Recent Orders for {$user->name}:\n\n";
        foreach ($orders as $order) {
            $status = strtoupper(str_replace('_', ' ', $order->order_status));
            $date = $order->created_at->format('M d, Y h:i A');
            $completion = $order->estimated_completion ? $order->estimated_completion->format('M d, Y h:i A') : 'TBD';
            $summary .= "• #{$order->order_number} — {$status}\n  Service: {$order->service->name} | Total: P".number_format($order->total_amount, 2)."\n  Ordered: {$date} | Est. Completion: {$completion}\n\n";
        }

        return $summary.'Need more details? Provide your order code (e.g. #HW-XXXXXX)!';
    }
}
