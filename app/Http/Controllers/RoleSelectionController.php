<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class RoleSelectionController extends Controller
{
    public function showSelection()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? \App\Models\Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        $roles = [];
        if (strpos($cargo, 'docente-decano') !== false) {
            $roles = ['docente', 'decano/a'];
        } elseif (strpos($cargo, 'docente-subdecano') !== false) {
            $roles = ['docente', 'subdecano/a'];
        } elseif (strpos($cargo, 'docente-coordinador') !== false) {
            $roles = ['docente', 'coordinador/a'];
        }
        if (empty($roles)) {
            return redirect()->route('home');
        }
        return view('auth.select_role', compact('roles'));
    }
    public function select(Request $request)
    {
        $role = $request->input('role');
        // Normaliza el rol seleccionado para que coincida con los checks de permisos
        $roleNorm = strtolower(trim($role));
        session(['selected_role' => $roleNorm]);
        return redirect()->route('home');
    }
}
