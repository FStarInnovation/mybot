<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Laravel\Horizon\Horizon;

class HealthController extends Controller
{
    /**
     * GET /api/health/mcp – lightweight health-check to verify:
     * 1. MCP route is accessible (this controller).
     * 2. Redis connection is reachable.
     * 3. Horizon has workers listening.
     * 4. The queue can accept a trivial job (not dispatched to avoid noise).
     */
    public function mcp(): JsonResponse
    {
        $redisOk = false;
        try {
            Redis::set('health:mcp', '1', 'EX', 5);
            $redisOk = Redis::get('health:mcp') === '1';
        } catch (\Throwable $e) {
            $redisOk = false;
        }

        $horizonStatus = method_exists(Horizon::class, 'status') ? Horizon::status() : 'unknown';
        $activeWorkers = collect(Horizon::supervisors())->sum('processes');

        return response()->json([
            'queue_connection' => config('queue.default'),
            'redis'            => $redisOk ? 'ok' : 'error',
            'horizon_status'   => $horizonStatus,
            'active_workers'   => $activeWorkers,
            'pending_jobs'     => Queue::size(),
        ]);
    }
}
