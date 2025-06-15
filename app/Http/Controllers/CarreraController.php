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
        $persona = $user ? ($user instanceof \App\Models\User ? $user->persona : $user) : null;
        if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'docente') {
            abort(403, 'No autorizado');
        }
    }

    // Mostrar todas las carreras
    public function index()
    {
        $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
    }
    
        $carreras = Carrera::all();
        return view('carreras.index', compact('carreras'));
    }

    // Mostrar el formulario para crear una nueva carrera
    public function create()
    {
        $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
    }
    
        return view('carreras.create');
    }

    // Almacenar una nueva carrera
    public function store(Request $request)
    {
        $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
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
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
    }
    
        return view('carreras.edit', compact('carrera'));
    }

    // Actualizar la carrera en la base de datos
    public function update(Request $request, Carrera $carrera)
    {
        $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
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
    {$user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
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
