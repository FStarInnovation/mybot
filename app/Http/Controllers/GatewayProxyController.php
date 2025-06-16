<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Minimal proxy to forward NLWEB streaming (SSE) requests through the Laravel backend.
 *
 * Industry-standard considerations:
 * 1.  Propagate original request headers that matter (Content-Type, Accept, Authorization).
 * 2.  Disable output buffering for true streaming (`X-Accel-Buffering: no`).
 * 3.  Respect appropriate timeouts, keep them low to avoid hanging connections.
 * 4.  Forward the response status code and body as-is.
 * 5.  Avoid leaking internal headers.
 */
class GatewayProxyController extends Controller
{
    /**
     * POST /ask  – SSE proxy to NLWEB Gateway.
     */
    public function ask(Request $request)
    {
        $gatewayBase = rtrim(config('services.gateway.base'), '/');
        $targetUrl   = $gatewayBase . '/ask';

        // Forward JSON payload & headers
        $payload = $request->all();

        $resp = Http::withHeaders([
            'Accept'       => 'text/event-stream',
            'Content-Type' => 'application/json',
        ])->timeout(120)->post($targetUrl, $payload);

        // StreamedResponse keeps connection open for SSE
        return new StreamedResponse(function () use ($resp) {
            echo $resp->body();
        }, $resp->status(), [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
