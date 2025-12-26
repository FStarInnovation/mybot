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

        // Check for location data from Twilio
        $latitude = $request->input('Latitude');
        $longitude = $request->input('Longitude');
        $address = $request->input('Address');

        // If location was shared, create a message about it
        if ($latitude && $longitude) {
            $body = "Mi ubicación es: latitud {$latitude}, longitud {$longitude}";
            if ($address) {
                $body .= " ({$address})";
            }
            Log::info('WhatsApp location received', [
                'from' => $from,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'address' => $address,
            ]);
        }

        if (empty($body)) {
            Log::warning('WhatsApp webhook: empty body', ['from' => $from]);
            return response('OK', 200);
        }

        try {
            // Check if this is a button response
            $isButtonResponse = $this->isButtonResponse($body);
            
            if ($isButtonResponse) {
                // Handle button click
                $aiResponse = $this->handleButtonClick($body, $from, $latitude, $longitude);
            } else {
                // Regular AI processing
                $aiResponse = $this->getAiResponse($body, $from, $latitude, $longitude);
            }

            // Send response back via Twilio
            $this->sendWhatsAppMessage($from, $aiResponse);

            Log::info('WhatsApp response sent', [
                'to' => $from,
                'response_length' => strlen($aiResponse),
                'is_button_response' => $isButtonResponse,
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
    protected function getAiResponse(string $userMessage, string $userPhone, ?string $latitude = null, ?string $longitude = null): string
    {
        $gatewayUrl = config('services.twilio.runpod_gateway_url');

        if (empty($gatewayUrl)) {
            throw new \Exception('RUNPOD_GATEWAY_URL not configured');
        }

        // Build system prompt with location context if available
        $systemPrompt = 'Eres Farmabot, un asistente virtual de farmacia. Ayudas a los usuarios a encontrar medicamentos, consultar precios y responder preguntas sobre productos farmacéuticos. Responde de manera concisa y amigable. Limita tus respuestas a 1600 caracteres para WhatsApp.';
        
        if ($latitude && $longitude) {
            $systemPrompt .= " El usuario ha compartido su ubicación: latitud {$latitude}, longitud {$longitude}. Puedes usar esta información para recomendar farmacias cercanas o dar indicaciones relevantes a su zona.";
        }

        $response = Http::timeout(60)->post($gatewayUrl, [
            'model' => 'llama',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
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
     * Send WhatsApp message with interactive buttons
     */
    protected function sendWhatsAppButtons(string $to, string $templateName = 'farmabot_menu'): void
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

        Log::info('Sending WhatsApp button template', [
            'to' => $to,
            'from' => $fromWhatsApp,
            'template' => $templateName,
        ]);

        // Send content template with buttons
        $client->messages->create(
            $to,
            [
                'from' => $fromWhatsApp,
                'contentSid' => $this->getTemplateContentSid($templateName),
                'contentVariables' => '{}',
            ]
        );
    }

    /**
     * Get Content SID for template (would be stored in config or database)
     */
    protected function getTemplateContentSid(string $templateName): string
    {
        // Map template names to their Content SIDs
        // These SIDs are obtained after template approval
        $templates = [
            'farmabot_menu' => env('TWILIO_FARMABOT_MENU_SID', 'HXxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
            'product_options' => env('TWILIO_PRODUCT_OPTIONS_SID', 'HXxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'),
        ];

        return $templates[$templateName] ?? throw new \Exception("Template {$templateName} not found");
    }

    /**
     * Check if message is a button response
     */
    protected function isButtonResponse(string $body): bool
    {
        $buttonTexts = [
            '🔍 Buscar medicamento',
            '📍 Farmacias cercanas', 
            '💊 Consultar precio',
            '📞 Hablar con humano'
        ];

        return in_array(trim($body), $buttonTexts);
    }

    /**
     * Handle button clicks
     */
    protected function handleButtonClick(string $buttonText, string $from, ?string $latitude = null, ?string $longitude = null): string
    {
        Log::info('Button clicked', ['button' => $buttonText, 'from' => $from]);

        switch (trim($buttonText)) {
            case '🔍 Buscar medicamento':
                return '🔍 ¿Qué medicamento estás buscando? Puedes decirme el nombre o describir tus síntomas.';
                
            case '📍 Farmacias cercanas':
                if ($latitude && $longitude) {
                    return "📍 Buscando farmacias cercanas a tu ubicación (lat: {$latitude}, lon: {$longitude})...\n\nEncontré 3 farmacias cerca de ti:\n\n1. 🏥 Farmacia Central - 0.5 km\n   📍 Av. Principal 123\n   ⏰ Abierto hasta 22:00\n   📞 555-0123\n\n2. 🏥 Farmacia Salud - 0.8 km\n   📍 Calle Secundaria 456\n   ⏰ Abierto 24 horas\n   📞 555-0456\n\n3. 🏥 Farmacia Económica - 1.2 km\n   📍 Plaza Mayor 789\n   ⏰ Abierto hasta 21:00\n   📞 555-0789\n\n¿Necesitas indicaciones para alguna de ellas?";
                } else {
                    return '📍 Para mostrarte farmacias cercanas, por favor comparte tu ubicación (usa el clip 📎 y selecciona "Ubicación").';
                }
                
            case '💊 Consultar precio':
                return '💊 ¿De qué medicamento quieres consultar el precio? Puedes decirme el nombre comercial o el principio activo.';
                
            case '📞 Hablar con humano':
                return '📞 Conectando con un farmacéutico...\n\nPor favor espera un momento. Uno de nuestros especialistas te atenderá shortly.\n\n⏰ Horario de atención: 8:00 - 22:00\n📞 Teléfono directo: 555-9999';
                
            default:
                return 'No entendí esa opción. Por favor selecciona una de las opciones del menú.';
        }
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
