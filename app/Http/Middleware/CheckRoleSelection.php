<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class CheckRoleSelection
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user) return $next($request);
        // Si el usuario debe cambiar la contraseña, no forzar selección de rol todavía
        if ($user->must_change_password) {
            return $next($request);
        }
        $persona = $user instanceof \App\Models\User ? \App\Models\Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        $cargoNorm = str_replace([' ', '_'], ['-', '-'], $cargo);
        $esDocenteDecano = (strpos($cargoNorm, 'docente') !== false && (strpos($cargoNorm, 'decano') !== false));
        $esDocenteSubdecano = (strpos($cargoNorm, 'docente') !== false && (strpos($cargoNorm, 'subdecano') !== false));
        if (($esDocenteDecano || $esDocenteSubdecano) && !session('selected_role')) {
            return redirect()->route('role.select.show');
        }
        return $next($request);
    }
}
