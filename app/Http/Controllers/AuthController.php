<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required'
        ]);

        // Limpiar espacios en el correo
        $correo = trim(str_replace(' ', '', $credentials['correo']));

        $persona = \App\Models\Persona::whereRaw('REPLACE(TRIM(correo), \' \', \'\') = ?', [$correo])->first();

        if ($persona && $credentials['password'] === $persona->cedula) {
            Auth::login($persona);
            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        return back()->withErrors([
            'correo' => 'Credenciales incorrectas.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:admin,user'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Usuario registrado correctamente.');
    }
}
