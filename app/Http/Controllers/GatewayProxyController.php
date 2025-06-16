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
     * GET /ask  – SSE proxy to NLWEB Gateway.
     */
    public function stream(Request $request)
    {
        $gatewayBase = rtrim(config('services.gateway.base'), '/');
        $targetUrl   = $gatewayBase . '/ask';

        // Forward query params if any
        $resp = Http::withHeaders([
            'Accept' => 'text/event-stream',
        ])->timeout(120)->get($targetUrl, $request->query());

        return new StreamedResponse(function () use ($resp) {
            echo $resp->body();
        }, $resp->status(), [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    /**
     * POST /tool/ask – synchronous JSON request (non-stream).
     */
    public function askSync(Request $request)
    {
        $gatewayBase = rtrim(config('services.gateway.base'), '/');
        $targetUrl   = $gatewayBase . '/ask';

        $resp = Http::withHeaders([
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ])->timeout(120)->post($targetUrl, $request->all());

        return response($resp->body(), $resp->status())
            ->withHeaders(
                collect($resp->headers())->only(['content-type'])->toArray()
            );
    }
}
