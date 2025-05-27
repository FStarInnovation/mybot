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
        // Returning default welcome view as placeholder
        return view('welcome');
    }

    /**
     * Handle form submission (stub).
     */
    public function handleForm(Request $request)
    {
        // Placeholder: redirect back
        return redirect()->back();
    }
}
