<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PushController extends Controller
{
    /**
     * POST /api/subscribe
     */
    public function subscribe(Request $request): JsonResponse
    {
        $data = $request->validate([
            'endpoint'   => 'required|url',
            'keys.p256dh' => 'required|string',
            'keys.auth'   => 'required|string',
        ]);

        $sub = PushSubscription::updateOrCreate(
            ['endpoint' => $data['endpoint']],
            [
                'public_key' => $data['keys']['p256dh'],
                'auth_token' => $data['keys']['auth'],
            ]
        );

        return response()->json(['id' => $sub->id]);
    }
}
