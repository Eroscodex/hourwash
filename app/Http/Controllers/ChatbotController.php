<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\QrCode;
use App\Models\Service;
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

        // 1. Guardrail Check: Intercept non-laundry queries (cooking, food, recipes, coding, general trivia)
        if (Str::contains($msg, ['pancit', 'cook', 'recipe', 'food', 'noodle', 'dish', 'ingredient', 'python', 'code', 'math', 'politic'])) {
            return response()->json([
                'reply' => 'I am the HourWash AI Assistant, specialized exclusively for Hour Wash Laundry Shop in Magallanes St., Orosite, Legazpi City! 🧺 I can help you track laundry orders, check operating hours (6:00 AM – 10:00 PM), or inspect service packages & rates. How can I assist with your laundry today?',
            ]);
        }

        // 2. OpenAI Cloud LLM API (Supported on Hostinger via standard HTTPS port 443)
        $openAiKey = env('OPENAI_API_KEY');
        if (! empty($openAiKey)) {
            try {
                $response = Http::timeout(4)
                    ->withToken($openAiKey)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => 'gpt-3.5-turbo',
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are STRICTLY the Virtual Assistant for Hour Wash Laundry Shop located in Magallanes St., Orosite, Legazpi City. DO NOT answer cooking recipes, food, coding, or general trivia. ONLY answer questions about laundry services, order status tracking, machine availability, and store hours. Politely decline any non-laundry questions.',
                            ],
                            [
                                'role' => 'user',
                                'content' => $request->message,
                            ],
                        ],
                        'max_tokens' => 150,
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

        // 3. Local Ollama LLM (For local development on 127.0.0.1:11434)
        try {
            $response = Http::timeout(2)->post('http://127.0.0.1:11434/api/chat', [
                'model' => 'gemma3:1b',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are STRICTLY the Virtual Assistant for Hour Wash Laundry Shop located in Magallanes St., Orosite, Legazpi City.',
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

        // 4. Zero-Dependency Smart Engine (Guaranteed 100% working on Hostinger shared hosting without external API dependencies)
        return response()->json([
            'reply' => $this->getDomainReply($msg),
        ]);
    }

    private function getDomainReply(string $msg): string
    {
        // Live Order / QR Code Tracking Lookup
        if (preg_match('/hw-?[a-z0-9]+/i', $msg, $matches) || preg_match('/[0-9a-f]{8}-[0-9a-f]{4}/i', $msg, $matches)) {
            $code = ltrim(trim($matches[0]), '#');
            $qr = QrCode::where('qr_token', $code)->first();
            $order = $qr ? Order::with('service')->find($qr->order_id) : Order::with('service')->where('order_number', $code)->first();

            if ($order) {
                $status = strtoupper(str_replace('_', ' ', $order->order_status));
                $completion = $order->estimated_completion ? $order->estimated_completion->format('M d, Y h:i A') : 'In Progress';

                return "Order #{$order->order_number} Status: {$status}. Service: {$order->service->name}. Estimated Completion: {$completion}.";
            }
        }

        if (Str::contains($msg, ['track', 'status', 'order', 'check', 'where is my'])) {
            return 'To check your laundry order status, enter your Order Code (e.g. HW884210) or QR Token in the tracking box!';
        }

        if (Str::contains($msg, ['hour', 'time', 'open', 'close', 'schedule'])) {
            return 'Hour Wash Laundry Shop is open daily from 6:00 AM to 10:00 PM in Magallanes St., Orosite, Legazpi City.';
        }

        if (Str::contains($msg, ['location', 'where', 'address', 'place', 'legazpi', 'orosite'])) {
            return 'We are located at Magallanes St., Orosite, Legazpi City, Albay, Philippines.';
        }

        if (Str::contains($msg, ['price', 'rate', 'cost', 'fee', 'package', 'service', 'wash', 'dry'])) {
            $services = Service::where('status', 'active')->pluck('name')->toArray();
            $servList = implode(', ', $services);

            return "Our active laundry services include: {$servList}. Wash & Dry packages start at ₱120/kg!";
        }

        if (Str::contains($msg, ['hi', 'hello', 'hey', 'good'])) {
            return 'Hello! Welcome to Hour Wash Laundry Shop. How can I assist you with your laundry orders today?';
        }

        return 'I am the HourWash Assistant dedicated strictly to Hour Wash Laundry Shop. You can ask me about order tracking, services & rates, or store hours (6:00 AM – 10:00 PM) in Magallanes St., Orosite, Legazpi City!';
    }
}
