<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Twilio\Rest\Client;

class WhatsAppController extends Controller
{
    /**
     * Handle incoming WhatsApp webhook from Twilio
     */
    public function webhook(Request $request)
    {
        Log::info('WhatsApp webhook received', $request->all());

        // Extract message data from Twilio webhook
        $from = $request->input('From'); // whatsapp:+5491157232768
        $body = $request->input('Body'); // User message text
        $messageSid = $request->input('MessageSid');
        $profileName = $request->input('ProfileName');

        if (empty($body)) {
            Log::warning('WhatsApp webhook: empty body', ['from' => $from]);
            return response('OK', 200);
        }

        try {
            // Send message to RunPod gateway for AI processing
            $aiResponse = $this->getAiResponse($body, $from);

            // Send response back via Twilio
            $this->sendWhatsAppMessage($from, $aiResponse);

            Log::info('WhatsApp response sent', [
                'to' => $from,
                'response_length' => strlen($aiResponse),
            ]);

        } catch (\Exception $e) {
            Log::error('WhatsApp webhook error', [
                'error' => $e->getMessage(),
                'from' => $from,
                'body' => $body,
            ]);

            // Try to send error message to user (but don't fail if Twilio not configured)
            try {
                $this->sendWhatsAppMessage(
                    $from,
                    'Lo siento, hubo un error procesando tu mensaje. Por favor intenta de nuevo.'
                );
            } catch (\Exception $twilioError) {
                Log::error('Failed to send error message via Twilio', [
                    'error' => $twilioError->getMessage(),
                ]);
            }
        }

        // Twilio expects 200 OK response
        return response('OK', 200);
    }

    /**
     * Get AI response from RunPod gateway
     */
    protected function getAiResponse(string $userMessage, string $userPhone): string
    {
        $gatewayUrl = config('services.twilio.runpod_gateway_url');

        if (empty($gatewayUrl)) {
            throw new \Exception('RUNPOD_GATEWAY_URL not configured');
        }

        $response = Http::timeout(60)->post($gatewayUrl, [
            'model' => 'llama',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Eres Farmabot, un asistente virtual de farmacia. Ayudas a los usuarios a encontrar medicamentos, consultar precios y responder preguntas sobre productos farmacéuticos. Responde de manera concisa y amigable. Limita tus respuestas a 1600 caracteres para WhatsApp.',
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage,
                ],
            ],
            'stream' => false,
            'max_tokens' => 500,
        ]);

        if (!$response->successful()) {
            Log::error('RunPod gateway error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('AI gateway returned error: ' . $response->status());
        }

        $data = $response->json();

        // Extract response text from gateway response
        // Adjust based on actual gateway response format
        $aiText = $data['response'] 
            ?? $data['choices'][0]['message']['content'] 
            ?? $data['message'] 
            ?? 'No pude procesar tu solicitud.';

        // Truncate to WhatsApp limit (1600 chars)
        if (strlen($aiText) > 1600) {
            $aiText = substr($aiText, 0, 1597) . '...';
        }

        return $aiText;
    }

    /**
     * Send WhatsApp message via Twilio
     */
    protected function sendWhatsAppMessage(string $to, string $message): void
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $fromNumber = config('services.twilio.whatsapp_number');

        if (empty($accountSid) || empty($authToken) || empty($fromNumber)) {
            throw new \Exception('Twilio credentials not configured');
        }

        $client = new Client($accountSid, $authToken);

        // Ensure from number has whatsapp: prefix
        $fromWhatsApp = str_starts_with($fromNumber, 'whatsapp:') 
            ? $fromNumber 
            : 'whatsapp:' . $fromNumber;

        Log::info('Sending WhatsApp message', [
            'to' => $to,
            'from' => $fromWhatsApp,
            'body_length' => strlen($message),
        ]);

        $client->messages->create(
            $to, // To: whatsapp:+5491157232768
            [
                'from' => $fromWhatsApp,
                'body' => $message,
            ]
        );
    }

    /**
     * Health check endpoint for webhook
     */
    public function health()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'whatsapp-webhook',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
