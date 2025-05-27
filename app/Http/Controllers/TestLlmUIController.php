<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestLlmUIController extends Controller
{
    /**
     * Display a test form (stub).
     */
    public function showForm()
    {
        return view('llm-form');
    }

    /**
     * Handle form submission with LLM query.
     */
    public function handleForm(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'query' => 'required|string|max:1000',
        ]);
        
        // Process the query (placeholder for actual LLM processing)
        $response = 'Response to: ' . $validated['query'];
        
        // Return response with original query
        return view('llm-response', [
            'query' => $validated['query'],
            'response' => $response
        ]);
    }
}
