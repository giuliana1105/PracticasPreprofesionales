<?php

namespace App\Http\Controllers;

use App\Models\EstadoTitulacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstadoTitulacionController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        // Cambia para usar el campo string 'cargo'
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'coordinadora','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'decana'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $estados = EstadoTitulacion::all();
        return view('estado_titulaciones.index', compact('estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        return view('estado_titulaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $request->validate([
            'nombre_estado' => 'required|string|max:255|unique:estado_titulaciones,nombre_estado'
        ]);

        EstadoTitulacion::create($request->all());

        return redirect()->route('estado-titulaciones.index')
                         ->with('success', 'Estado de titulación creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EstadoTitulacion $estadoTitulacion)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        return view('estado_titulaciones.show', compact('estadoTitulacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $estado = \App\Models\EstadoTitulacion::findOrFail($id);
        return view('estado_titulaciones.edit', compact('estado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana', 'subdecano/a','abogado', 'abogada', 'abogado/a','docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $estado = \App\Models\EstadoTitulacion::findOrFail($id);

        $request->validate([
            'nombre_estado' => 'required|string|max:255',
        ]);

        $estado->nombre_estado = $request->nombre_estado;
        $estado->save();

        return redirect()->route('estado-titulaciones.index')
            ->with('success', 'Estado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $estado = \App\Models\EstadoTitulacion::findOrFail($id);

        // Verifica si está referenciado en titulaciones
        if ($estado->titulaciones()->exists()) {
            return redirect()->route('estado-titulaciones.index')
                ->with('error', 'No se puede eliminar este estado porque está referenciado en otras tablas.');
        }

        $estado->delete();

        return redirect()->route('estado-titulaciones.index')
            ->with('success', 'Estado eliminado exitosamente.');
    }
}