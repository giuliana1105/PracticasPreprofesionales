<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class PasswordChangeController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.passwords.change');
    }

    public function change(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Usuario no autenticado');
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        // Detect combined roles and redirect to role selection if needed
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
        if (in_array(strtolower($user->cargo), array_map('strtolower', $combinedRoles))) {
            // Limpiar el rol seleccionado para forzar la selección después del cambio de contraseña
            session()->forget('selected_role');
            return redirect()->route('role.select.show')->with('success', 'Contraseña actualizada correctamente. Por favor seleccione su rol.');
        }

        return redirect()->route('home')->with('success', 'Contraseña actualizada correctamente.');
    }
}
