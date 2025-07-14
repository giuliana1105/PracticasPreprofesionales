<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarreraController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        // Solo restringe para cargos que no pueden ver el listado (secretaria/o sí puede ver)
        if (in_array($cargo, [
            'coordinador','coordinadora','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana', 'subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'decana'
        ])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
    }

    // Mostrar todas las carreras
    public function index()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        // Solo restringe para cargos que no pueden ver el listado (secretaria/o sí puede ver)
        if (in_array($cargo, [
            'coordinador','coordinador/a', 'decano','decano/a', 'subdecano','subdecano/a', 'subdecana', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante'
        ])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $carreras = Carrera::all();
        return view('carreras.index', compact('carreras'));
    }

    // Mostrar el formulario para crear una nueva carrera
    public function create()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        // Ahora también restringe a secretaria/o
        if (in_array($cargo, [
            'coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada', 'abogado/a','docente', 'estudiante','secretario/a', 'secretario', 'secretaria'
        ])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        return view('carreras.create');
    }

    // Almacenar una nueva carrera
    public function store(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        // Ahora también restringe a secretaria/o
        if (in_array($cargo, [
            'coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario','secretario/a', 'secretaria'
        ])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $request->validate([
            'nombre_carrera' => 'required|max:255',
            'siglas_carrera' => 'required|max:10',
        ]);

        Carrera::create($request->all());

        return redirect()->route('carreras.index')->with('success', 'Carrera creada exitosamente.');
    }

    // Mostrar el formulario para editar una carrera existente
    public function edit(Carrera $carrera)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        // Ahora también restringe a secretaria/o
        if (in_array($cargo, [
            'coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante','secretario/a', 'secretario', 'secretaria'
        ])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        return view('carreras.edit', compact('carrera'));
    }

    // Actualizar la carrera en la base de datos
    public function update(Request $request, Carrera $carrera)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        // Ahora también restringe a secretaria/o
        if (in_array($cargo, [
            'coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante','secretario/a', 'secretario', 'secretaria'
        ])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $request->validate([
            'nombre_carrera' => 'required|max:255',
            'siglas_carrera' => 'required|max:10',
        ]);

        $carrera->update($request->all());

        return redirect()->route('carreras.index')->with('success', 'Carrera actualizada exitosamente.');
    }

    // Eliminar una carrera
    public function destroy(Carrera $carrera)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        // Ahora también restringe a secretaria/o
        if (in_array($cargo, [
            'coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario','secretario/a', 'secretaria'
        ])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        // Verifica si la carrera está referenciada en personas
        if (\App\Models\Persona::where('carrera_id', $carrera->id_carrera)->exists()) {
            return redirect()->route('carreras.index')
                ->with('error', 'No se puede eliminar este estado porque está referenciado en otras tablas.');
        }

        $carrera->delete();
        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada exitosamente.');
    }
}
