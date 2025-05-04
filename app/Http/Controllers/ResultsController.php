<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ResultsController extends Controller
{
    /**
     * GET /api/results
     */
    public function index(): JsonResponse
    {
        $results = Cache::get('scan_results', []);
        return response()->json(['results' => $results]);
    }
}