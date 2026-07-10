<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        try {
            $response = Http::post('http://127.0.0.1:11434/api/chat', [
                'model' => 'gemma3:1b',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => <<<PROMPT
                    You are HourWash AI, the virtual assistant for HourWash Laundry Shop.

                    Your responsibilities are:
                    - Answer customer questions about HourWash services.
                    - Explain washing, drying, folding, pickup, and delivery services.
                    - Inform customers about operating hours and pricing if available.
                    - Help customers check their laundry order status.
                    - Help customers identify their laundry using the QR tag.
                    - Be polite, professional, and concise.

                    Order Status Rules:
                    - If a customer asks about an order, ask for their Order ID or QR Tag code.
                    - Do not invent order information.
                    - Wait for the system to provide the actual order details.

                    QR Tag Rules:
                    - If a customer has a QR tag, ask them to enter the QR code or scan it.
                    - Explain that the QR tag is used to identify and track their laundry.

                    If you don't know an answer, politely tell the customer that a staff member can assist them.

                    Never make up order status or customer information.
                    PROMPT
                    ],
                    [
                        'role' => 'user',
                        'content' => $request->message
                    ]
                ],
                'stream' => false
            ]);

            $data = $response->json();

            return response()->json([
                'reply' => $data['message']['content'] ?? 'No response.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'reply' => $e->getMessage()
            ], 500);
        }
    }
}