<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\Order;
use App\Models\QrCode;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $authUser = Auth::user();

        // 1. Guardrail Check: Intercept non-laundry queries
        if (Str::contains($msg, ['pancit', 'cook', 'recipe', 'food', 'noodle', 'dish', 'ingredient', 'python', 'code', 'math', 'politic'])) {
            return response()->json([
                'reply' => 'I am the HourWash AI Assistant, specialized exclusively for Hour Wash Laundry Shop in Magallanes St., Orosite, Legazpi City! I can help you with services, prices, pickup & delivery, store hours (7:30 AM – 6:00 PM daily • cut-off: 4:30 PM), or tracking orders. How can I assist with your laundry today?',
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
                        'max_tokens' => 350,
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
     * Build a role-tailored system prompt with live context.
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

        $faqContext = <<<'FAQ'
HOUR WASH LAUNDRY SHOP FULL FAQ & KNOWLEDGE BASE:
1. SERVICES OFFERED:
   - Full Wash-Dry-Fold Service: P200 (Drop-off).
   - Wash Only: P75 / load (up to 7kg).
   - Dry Only: P75 / load (up to 7kg).
   - Fold Only: P50 / load.
   - Self-Service Laundry: P150 / load (Operate commercial washers & dryers yourself).
   - Heavy Blankets & Comforters: P200 / load (Commercial machines up to 15kg capacity).
   - Delicate Clothes & Steam Care: Gentle wash cycle available upon request.
   - Curtains & Fabric Covers: Yes, we wash heavy curtains and cushion covers.
   - Shoes & Bags: Gentle fabric washing available; specialty leather deep clean inquiries at shop counter.
   - Ironing / Pressing: Standard folding is included FREE; steam pressing available upon request for nominal fee.
   - Dry Cleaning: Wet-wash & steam care for suits/jackets available (2-3 days turnaround).

2. PRICING & DISCOUNTS:
   - Price per load (up to 7kg standard): Wash Only P75, Dry Only P75, Fold Only P50, Self-Service P150, Full Service P200.
   - Detergent & Fabric Softener: INCLUDED FREE in all Full Service & Wash packages! You can also request specific brands or bring your own.
   - Discounts & Rewards: Frequent User Card available! Earn 1 stamp per order — 12 stamps get you a FREE wash reward or discount. Bulk order discounts available.
   - Heavily Soiled Clothes: Standard loads have no extra charge. Extremely muddy or heavily stained items requiring pre-soak treatment have a nominal P20-P50 add-on fee.
   - Payment Methods: Cash at Shop Counter.

3. TURNAROUND TIME & STORE DROP-OFF:
   - Standard Turnaround: 2 to 4 hours.
   - Same-Day Service: YES! Orders submitted before 4:30 PM cut-off are completed on the same day.
   - Express Service: Fast-track 2-hour turnaround available upon request.
   - Order Tracking: Track live cleaning status on our website via Order Code (e.g. #HW-XXXXXX) or scanning the receipt QR tag.

4. LOCATION & STORE HOURS:
   - Address: Magallanes St., Orosite, Legazpi City, Albay, Philippines.
   - Store Hours: 7:30 AM – 6:00 PM Daily (OPEN MONDAY TO SUNDAY!).
   - Walk-ins & Appointments: WALK-INS ARE ALWAYS WELCOME! No appointment necessary.

5. SPECIAL HANDLING & WASHING INSTRUCTIONS:
   - Color Separation: YES, white and colored clothes are separated upon request.
   - Shrinkage Protection: Commercial temperature controls are used to protect garments from shrinking.
   - Special Instructions: You can type custom instructions (detergent preference, water temp, delicate cycle) in your order remarks!
FAQ;

        if ($role === 'guest') {
            return <<<PROMPT
You are the Public Storefront AI Assistant for Hour Wash Laundry Shop located in Magallanes St., Orosite, Legazpi City, Albay, assisting store visitors.

MULTILINGUAL RULE:
- Understand ANY language or dialect (English, Tagalog, Bikolano, etc.) and respond fluently in the SAME language.

{$faqContext}

SERVICES & RATES SUMMARY:
{$serviceList}

CRITICAL RULES:
- Answer storefront questions friendly, clearly, and text-only (no emojis).
- Provide accurate prices, hours, pickup/delivery info, and laundry care instructions.
PROMPT;
        }

        if ($role === 'customer') {
            $myOrders = $this->getCustomerOrderSummary($user);

            return <<<PROMPT
You are the Customer Portal AI Assistant for Hour Wash Laundry Shop, assisting logged-in customer {$userName}.

MULTILINGUAL RULE:
- Understand ANY language/dialect (English, Tagalog, Bikolano, etc.) and respond fluently in the user's language.

{$myOrders}

{$faqContext}

SERVICES AVAILABLE:
{$serviceList}

SCOPING:
- Assist customer {$userName} with their active orders, tracking status, rates, FAQs, online booking, profile updates, and feedback.
- Keep responses text-only, friendly, and helpful.
PROMPT;
        }

        if ($role === 'staff') {
            $machines = Machine::all(['machine_name', 'machine_type', 'status']);
            $washersIdle = $machines->where('machine_type', 'washer')->where('status', 'idle')->count();
            $dryersIdle = $machines->where('machine_type', 'dryer')->where('status', 'idle')->count();
            $inShopCount = Order::whereIn('order_status', ['received', 'washing', 'rinsing', 'drying', 'finish'])->count();

            return <<<PROMPT
You are the Staff Operations AI Assistant for Hour Wash Laundry Shop, assisting staff member {$userName}.

{$faqContext}

IN-SHOP STATUS:
- Washers Idle: {$washersIdle} / 5
- Dryers Idle: {$dryersIdle} / 5
- Active In-Shop Orders: {$inShopCount}

SCOPING:
- Assist staff with machine statuses, stage updates (received -> washing -> rinsing -> drying -> finish), and customer inquiry FAQs.
PROMPT;
        }

        if ($role === 'admin' || $role === 'owner') {
            $totalOrders = Order::count();
            $todayPaidRevenue = Order::where('payment_status', 'paid')->whereDate('updated_at', today())->sum('total_amount');
            $totalUsers = User::count();

            return <<<PROMPT
You are the Admin Management AI Assistant for Hour Wash Laundry Shop, assisting {$userName} (Role: {$user->role}).

{$faqContext}

ADMIN STATS:
- Lifetime Orders: {$totalOrders}
- Today's Paid Revenue: P{$todayPaidRevenue}
- Total Accounts: {$totalUsers}

SCOPING:
- Assist admin/owner with store analytics, revenue, FAQs, user accounts, SMS/Email logs, and machine settings.
PROMPT;
        }

        return 'You are the Hour Wash AI Assistant for Hour Wash Laundry Shop.';
    }

    /**
     * Fallback smart domain engine with role-based scoping and comprehensive FAQ recognition.
     */
    private function getDomainReply(string $msg, ?User $user): string
    {
        $role = $user ? $user->role : 'guest';

        // 1. Order Tracking by Order Number / QR Token (e.g. #HW-1ICYHQUM or UUID)
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

        // 2. Services Offered & Wash-Dry-Fold Inquiry
        if (Str::contains($msg, ['what laundry services', 'services do you offer', 'wash-dry-fold', 'wash dry fold', 'what services', 'services offered', 'mga serbisyo'])) {
            return "Hour Wash Laundry Services Offered:\n1. Full Wash-Dry-Fold: P200 (Drop-off)\n2. Wash Only: P75 / load (up to 7kg)\n3. Dry Only: P75 / load (up to 7kg)\n4. Fold Only: P50 / load\n5. Self-Service Laundry: P150 / load (Operate commercial washers & dryers yourself)\n6. Heavy Blankets & Comforters: P200 / load";
        }

        // 3. Dry Cleaning Inquiry
        if (Str::contains($msg, ['dry clean', 'dry-cleaning', 'drycleaning', 'suit', 'tuxedo', 'barong'])) {
            return "Dry Cleaning & Suit Care Information:\nWe offer wet-washing, gentle fabric care, and steam processing for jackets, suits, and barongs (2 to 3 days turnaround). For specialized chemical dry cleaning inquiries, please ask our store counter at Magallanes St., Orosite or call (052) 800-HOURWASH!";
        }

        // 4. Blankets, Comforters & Heavy Items Inquiry
        if (Str::contains($msg, ['blanket', 'blankets', 'comforter', 'comforters', 'bedsheet', 'bedsheets', 'duvet', 'kumot'])) {
            return "Washing Blankets & Comforters:\nYES! We have heavy-duty commercial washers (7kg to 15kg capacity) specially designed for thick blankets, bedsheets, comforters, and duvet covers at P200 per load!";
        }

        // 5. Shoes, Bags, Curtains Inquiry
        if (Str::contains($msg, ['shoe', 'shoes', 'bag', 'bags', 'curtain', 'curtains', 'sapatos', 'bagting', 'kurtina'])) {
            return "Shoes, Bags & Curtains Cleaning:\n- Curtains & Fabric Covers: YES! We wash heavy curtains, bedspreads, and fabric sofa covers.\n- Shoes & Bags: We offer gentle fabric washing. For specialty leather deep cleaning, please inquire at our shop counter or call (052) 800-HOURWASH.";
        }

        // 6. Ironing & Pressing Services Inquiry
        if (Str::contains($msg, ['iron', 'ironing', 'press', 'pressing', 'plancha', 'plantsa'])) {
            return "Ironing & Pressing Services:\nYES! Standard neat folding is included FREE in our Full Service package. Steam pressing/ironing is available upon request for a nominal add-on fee per garment.";
        }

        // 7. Self-Service Laundry Inquiry
        if (Str::contains($msg, ['self-service', 'self service', 'ako maglalaba', 'sarili'])) {
            return "Self-Service Laundry Information:\nYES! We offer Self-Service Commercial Washer & Dryer usage at P150 per load. You can load and operate our high-efficiency machines yourself at our store in Magallanes St., Orosite, Legazpi City!";
        }

        // 8. Prices, Per Kilo Cost & Blanket Cost
        if (Str::contains($msg, ['how much does it cost', 'cost to wash', 'price per kilo', 'per kilo', 'magkano magpalaba', 'magkano per kilo', 'magkano kumot', 'cost to wash a blanket', 'magkano laba'])) {
            return "Hour Wash Pricing & Rates:\n- Price Per Load (up to 7kg standard): Wash Only P75, Dry Only P75, Fold Only P50\n- Self-Service (Wash + Dry): P150 / load\n- Full Service (Wash + Dry + Fold): P200 (Drop-off)\n- Heavy Blanket / Comforter: P200 / load";
        }

        // 9. Package Deals, Discounts & Stamps Inquiry
        if (Str::contains($msg, ['package deal', 'package deals', 'discount', 'discounts', 'promo', 'stamp', 'stamps', 'frequent user', 'reward', 'rewards', 'mura'])) {
            return "Package Deals & Discounts:\nYES! We offer the Frequent User Loyalty Card — earn 1 stamp per order, and 12 stamps get you a FREE wash reward or discount! We also offer bulk load discounts and special full-service packages (P200 drop-off).";
        }

        // 10. Detergent & Fabric Softener Inquiry
        if (Str::contains($msg, ['detergent', 'softener', 'fabric softener', 'included in the price', 'sabon', 'downy', 'surf', 'ariel'])) {
            return "Detergent & Fabric Softener Policy:\nYES! Premium detergent and fabric softener are INCLUDED FREE in all Full Service & Wash packages! You can also request specific store detergent or bring your own preferred laundry detergent brand.";
        }

        // 11. Heavily Soiled Clothes Inquiry
        if (Str::contains($msg, ['heavily soiled', 'soiled', 'mud', 'muddy', 'stained', 'stains', 'madumi', 'dumi'])) {
            return "Heavily Soiled Clothes Policy:\nStandard soiled loads have NO extra charge! For garments with heavy mud or tough stains requiring extra pre-soak treatment, a nominal P20–P50 pre-treatment fee applies.";
        }

        // 12. Turnaround Time, Express & Same-Day Inquiry
        if (Str::contains($msg, ['how long', 'how long does', 'same day', 'same-day', 'express', 'turnaround', 'pick up my clothes', 'kelan makukuha', 'kailan makukuha', 'matatapos'])) {
            return "Laundry Turnaround Time & Same-Day Service:\n- Standard Processing: 2 to 4 hours\n- Same-Day Service: YES! Orders dropped off before our 4:30 PM cut-off are ready on the same day!\n- Express Service: Fast-track 2-hour processing available upon request.";
        }

        // 13. Location, Hours, Sundays, Walk-ins & Appointments
        if (Str::contains($msg, ['location', 'located', 'where is', 'address', 'open and close', 'store hours', 'sundays', 'sunday', 'appointment', 'walk-in', 'walk-ins', 'walk in', 'oras', 'saan', 'lugar', 'linggo'])) {
            return "Hour Wash Shop Location & Operating Hours:\n- Address: Magallanes St., Orosite, Legazpi City, Albay, Philippines\n- Store Hours: 7:30 AM – 6:00 PM Daily (OPEN MONDAY TO SUNDAY!)\n- Same-Day Cut-Off: 4:30 PM\n- Walk-ins & Appointments: WALK-INS ARE ALWAYS WELCOME! No appointment required.";
        }

        // 14. Payment Methods Inquiry
        if (Str::contains($msg, ['payment method', 'payment methods', 'pay', 'cash', 'cod', 'cash on delivery', 'bayad', 'paano magbayad'])) {
            return "Payment Methods Accepted:\n- Cash at Shop Counter upon drop-off or claim";
        }

        // 16. Delicate Clothes, Whites & Color Separation, Shrinkage & Custom Instructions
        if (Str::contains($msg, ['delicate', 'sensitive', 'white and color', 'separate', 'color', 'shrink', 'shrinkage', 'special instructions', 'hiwalay', 'puti', 'puting'])) {
            return "Garment Care & Special Handling:\n- Delicate Clothes: Gentle wash & low-temp drying available upon request.\n- Whites & Colors: YES! We separate white and colored clothes upon request.\n- Shrinkage Protection: Commercial temperature controls prevent fabric shrinkage.\n- Special Instructions: Type your specific washing or detergent preference in your order remarks!";
        }

        // 17. Welcome Page / Public Storefront Scoping (For Visitors & Guest Chatbot)
        if ($role === 'guest') {
            if (Str::contains($msg, ['how it works', 'how to order', 'process', 'steps', 'workflow', 'paano', 'papanano', 'hakbang'])) {
                return "How Hour Wash Laundry Shop Works:\n1. Select Service Package (Wash P75, Dry P75, Fold P50, Self-Service P150, Full Service P200/P250)\n2. Drop Off or Request Pickup: Drop off at shop or our rider collects from your address\n3. Cleaning Cycle: Professional Wash, Rinse, Dry & Fold\n4. Live Tracking: Track status on your phone via Order # (e.g. #HW-XXXXXX) or QR Tag\n5. Delivery or Claim: Claim at shop or get clean laundry delivered to your doorstep!";
            }

            if (Str::contains($msg, ['review', 'reviews', 'rating', 'ratings', 'feedback'])) {
                return "Customer Reviews & Quality Assurance:\nHour Wash Laundry Shop prides itself on fast, clean, and reliable service in Legazpi City! Logged-in customers can submit ratings and feedback directly on their dashboard after completing an order.";
            }

            if (Str::contains($msg, ['about us', 'about', 'background', 'shop info'])) {
                return "About Hour Wash Laundry Shop:\nWe are Legazpi City's premier laundry management system located in Magallanes St., Orosite. We offer fast, hygienic, and affordable wash, dry, fold, and doorstep pickup & delivery services.";
            }

            if (Str::contains($msg, ['developer', 'developers', 'creator', 'built', 'team', 'who made'])) {
                return "Hour Wash System Developers:\nDeveloped by Eroscodex Team using Laravel 11, Tailwind CSS, PHP 8.5, and Vite asset bundling.";
            }

            if (Str::contains($msg, ['privacy', 'security', 'terms', 'condition', 'policy'])) {
                return "Privacy Policy & Terms Summary:\n- Customer addresses, phone numbers, and order histories are kept strictly confidential.\n- Cash on Delivery (COD) and Cash at Counter accepted.\n- Same-day turnaround for orders submitted before 4:30 PM cut-off.";
            }
        }

        // 18. Customer Order Lookup by Name/Email
        if (Str::contains($msg, ['my order', 'my laundry', 'check order', 'track order', 'status', 'nasaan', 'nasaan na', 'asaan', 'hain', 'kelan', 'kailan'])) {
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

            return 'To track your laundry order, please tell me your Order Code (e.g. #HW-XXXXXX) or your registered email address!';
        }

        // 19. Contact & Support
        if (Str::contains($msg, ['contact', 'support', 'report', 'email', 'help', 'hotline', 'phone', 'tumawag'])) {
            return "Hour Wash Customer Support & Technical Team:\n- Shop Hotline: (052) 800-HOURWASH / 09100317744\n- Store Address: Magallanes St., Orosite, Legazpi City, Albay\n- Email Developer Support: karlnicko2019@gmail.com\n- Developer Team Page: https://hourwash.onrender.com/developers";
        }

        // 20. Greetings
        if (Str::contains($msg, ['hi', 'hello', 'hey', 'good', 'kumusta', 'musta', 'marhay'])) {
            if ($role === 'guest') {
                return "Hello / Marhay na aldaw! Welcome to Hour Wash Laundry Shop! I can assist you with:\n- Services & Rates (Wash, Dry, Fold, Self-Service, Pickup & Delivery)\n- Special Garments (Blankets, Comforters, Curtains, Delicate Clothes)\n- Store Hours & Location (Magallanes St., Orosite • 7:30 AM – 6:00 PM Daily)\n- Track Order (#HW-XXXXXX)\n- Customer Support & Developer Email (karlnicko2019@gmail.com)\n\nHow can I help you today?";
            }

            return "Hello {$user->name}! Welcome back to Hour Wash Laundry Portal! How can I assist you with your dashboard today?";
        }

        // 21. General Multilingual Storefront Fallback
        return "Hour Wash Laundry Shop AI Assistant:\n- Location: Magallanes St., Orosite, Legazpi City\n- Store Hours: 7:30 AM – 6:00 PM Daily (Cut-Off: 4:30 PM • Open Sundays!)\n- Customer Support & Developer Email: karlnicko2019@gmail.com\n- Services & Rates: Wash Only (P75), Dry Only (P75), Fold Only (P50), Self-Service (P150), Full-Service (P200/P250), Blankets & Comforters (P200)\n- Track Order: Provide your Order Code (e.g. #HW-XXXXXX) to view live status!";
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
