<?php

namespace App\Policies;

use App\Models\Titulacion;
use App\Models\User;

class TitulacionPolicy
{
    // Permite ver el listado (index) a todos los usuarios autenticados
    public function viewAny(User $user)
    {
        return true;
    }

    // Permite ver una titulación específica
    public function view(User $user, Titulacion $titulacion)
    {
        if ($user->role === 'estudiante') {
            // Solo puede ver sus propias titulaciones
            $persona = $user->persona;
            return $persona && $titulacion->cedula_estudiante === $persona->cedula;
        }
        if ($user->role === 'docente') {
            // Solo puede ver titulaciones donde es director o asesor
            $persona = $user->persona;
            return $persona && ($titulacion->cedula_director === $persona->cedula || $titulacion->cedula_asesor1 === $persona->cedula);
        }
        // Otros roles pueden ver todo
        return true;
    }

    // Permite crear titulaciones solo a admin, coordinador, decano
    public function create(User $user)
    {
        return in_array($user->role, ['administrador', 'coordinador', 'decano']);
    }

    // Permite editar titulaciones
    public function update(User $user, Titulacion $titulacion)
    {
        if ($user->role === 'docente') {
            $persona = $user->persona;
            // Solo si es director o asesor1
            return $persona && ($titulacion->cedula_director === $persona->cedula || $titulacion->cedula_asesor1 === $persona->cedula);
        }
        // Solo admin, coordinador, decano pueden editar todo
        return in_array($user->role, ['administrador', 'coordinador', 'decano']);
    }

    // Permite eliminar solo a admin
    public function delete(User $user, Titulacion $titulacion)
    {
        return $user->role === 'administrador';
    }

    // Permite generar PDF solo a admin, coordinador, decano
    public function generarPdf(User $user)
    {
        return in_array($user->role, ['administrador', 'coordinador', 'decano']);
    }

    // Permite cambiar resoluciones solo a admin
    public function cambiarResoluciones(User $user)
    {
        return $user->role === 'administrador';
    }

    // Permite ver filtros avanzados solo a admin, coordinador, decano, docente
    public function viewFilters(User $user)
    {
        return in_array($user->role, ['administrador', 'coordinador', 'decano', 'docente']);
    }
}
