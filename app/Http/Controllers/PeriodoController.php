<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeriodoController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        // Cambia para usar el campo string 'cargo'
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
    }

    public function index()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $periodos = Periodo::all();
        return view('periodos.index', compact('periodos'));
    }

    public function create()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        return view('periodos.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $request->validate([
            'mes_ini' => 'required|string|max:255',
            'mes_fin' => 'required|string|max:255',
            'año_ini' => 'required|integer',
            'año_fin' => 'required|integer',
        ]);

        $periodoAcademico = $request->mes_ini . ' ' . $request->año_ini . ' - ' . $request->mes_fin . ' ' . $request->año_fin;

        Periodo::create([
            'mes_ini' => $request->mes_ini,
            'mes_fin' => $request->mes_fin,
            'año_ini' => $request->año_ini,
            'año_fin' => $request->año_fin,
            'periodo_academico' => $periodoAcademico,
        ]);

        return redirect()->route('periodos.index')->with('success', 'Periodo creado exitosamente.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $periodo = Periodo::findOrFail($id);
        return view('periodos.edit', compact('periodo'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $request->validate([
            'mes_ini' => 'required|string|max:255',
            'mes_fin' => 'required|string|max:255',
            'año_ini' => 'required|integer',
            'año_fin' => 'required|integer',
        ]);

        $periodo = Periodo::findOrFail($id);

        $periodoAcademico = $request->mes_ini . ' ' . $request->año_ini . ' - ' . $request->mes_fin . ' ' . $request->año_fin;

        $periodo->update([
            'mes_ini' => $request->mes_ini,
            'mes_fin' => $request->mes_fin,
            'año_ini' => $request->año_ini,
            'año_fin' => $request->año_fin,
            'periodo_academico' => $periodoAcademico,
        ]);

        return redirect()->route('periodos.index')->with('success', 'Periodo actualizado correctamente.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $periodo = Periodo::findOrFail($id);

        // Verifica si el periodo está referenciado en titulaciones
        if (\App\Models\Titulacion::where('periodo_id', $periodo->id_periodo)->exists()) {
            return redirect()->route('periodos.index')
                ->with('error', 'No se puede eliminar este estado porque está referenciado en otras tablas.');
        }

        $periodo->delete();

        return redirect()->route('periodos.index')->with('success', 'Periodo eliminado exitosamente.');
    }
}
