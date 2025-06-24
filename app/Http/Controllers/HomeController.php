<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Muestra el menú principal.
     */
    public function index()
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            return redirect()->route('password.change');
        }

        return view('home');
    }
}
