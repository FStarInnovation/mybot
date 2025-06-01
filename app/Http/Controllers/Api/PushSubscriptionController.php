<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PushSubscriptionController extends Controller
{
    /**
     * Store a new push subscription
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required|url',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        $subscription = PushSubscription::updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'public_key' => $request->input('keys.p256dh'),
                'auth_token' => $request->input('keys.auth'),
                'user_agent' => $request->userAgent(),
            ]
        );

        Log::info('New push subscription', [
            'id' => $subscription->id,
            'endpoint' => $subscription->endpoint,
            'user_agent' => $subscription->user_agent,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Remove a push subscription
     */
    public function destroy(Request $request)
    {
        $request->validate(['endpoint' => 'required|url']);
        
        $deleted = PushSubscription::where('endpoint', $request->endpoint)->delete();
        
        if ($deleted) {
            Log::info('Push subscription deleted', ['endpoint' => $request->endpoint]);
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}
