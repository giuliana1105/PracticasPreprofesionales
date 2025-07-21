<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */


     public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Detect combined roles and redirect to role selection if needed
        $user = Auth::user();
        $combinedRoles = [
            'docente-decano/a',
            'docente-subdecano/a',
            'docente-decano',
            'docente-subdecano',
            'docente-decanoa',
            'docente-subdecanoa',
        ];
        if ($user && in_array(strtolower($user->cargo), array_map('strtolower', $combinedRoles))) {
            return redirect()->route('role.select.show');
        }
        return redirect()->intended('/home');
    }

    return back()->withErrors([
        'email' => __('auth.failed'),
    ]);
}

    // public function store(LoginRequest $request): RedirectResponse
    
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     //return redirect()->intended(route('dashboard', absolute: false));
    //     return redirect()->intended('/home');
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirigir directamente al login
        return redirect()->route('login');
    }
}
