<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpaController extends Controller
{
    /**
     * Обслуживает SPA (Single Page Application) для клиентской маршрутизации.
     *
     * @return \Illuminate\Http\Response
     */
    public function serve()
    {
        return response()->file(public_path('build/index.html'));
    }
}
