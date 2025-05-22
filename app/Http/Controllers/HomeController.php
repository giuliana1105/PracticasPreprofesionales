<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Muestra el menú principal.
     */
    public function index()
    {
        return view('home');
    }
}
