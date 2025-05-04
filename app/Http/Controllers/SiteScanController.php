<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Jobs\SiteScanJob;

class SiteScanController extends Controller
{
    /**
     * POST /api/scan-sites
     */
    public function scan(Request $request): JsonResponse
    {
        $data = $request->validate([
            'operators' => 'required|array|min:1',
            'query'     => 'required|string',
        ]);

        foreach ($data['operators'] as $operator) {
            SiteScanJob::dispatch($operator, $data['query']);
        }

        return response()->json(['status' => 'queued']);
    }
}