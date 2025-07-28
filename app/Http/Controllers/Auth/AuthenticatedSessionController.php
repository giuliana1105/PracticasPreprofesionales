<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $user = Auth::user();
        // Si debe cambiar la contraseña, ir primero a esa pantalla
        if ($user && $user->must_change_password) {
            return redirect()->route('password.change');
        }
        // Si no debe cambiar la contraseña, forzar selección de rol si corresponde
        $combinedRoles = [
            'docente-decano/a',
            'docente-subdecano/a',
            'docente-coordinador/a',
            'docente-decano',
            'docente-subdecano',
            'docente-coordinador',
            'docente-decanoa',
            'docente-subdecanoa',
            'docente-coordinadora',
        ];
        
        // Debug: verificar el cargo del usuario
        Log::info('Usuario cargo: ' . $user->cargo);
        Log::info('Cargo en lowercase: ' . strtolower($user->cargo));
        
        if ($user && in_array(strtolower($user->cargo), array_map('strtolower', $combinedRoles))) {
            session()->forget('selected_role');
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
