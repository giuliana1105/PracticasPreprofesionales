<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CargoController extends Controller
{
    // Mostrar todos los cargos
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
        $cargos = Cargo::all();
        return view('cargos.index', compact('cargos'));
    }

    // Mostrar el formulario para crear un nuevo cargo
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
        return view('cargos.create');
    }

    // Almacenar un nuevo cargo
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
            'nombre_cargo' => 'required|max:255',
            'siglas_cargo' => 'required|max:10',
        ]);

        Cargo::create($request->all());

        return redirect()->route('cargos.index')->with('success', 'Cargo creado exitosamente.');
    }

    // Mostrar el formulario para editar un cargo existente
    public function edit($id)
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
    
        $cargo = Cargo::findOrFail($id);
        return view('cargos.edit', compact('cargo'));
    }

    // Actualizar el cargo en la base de datos
    public function update(Request $request, $id)
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
            'nombre_cargo' => 'required|max:255',
            'siglas_cargo' => 'required|max:10',
        ]);

        $cargo = Cargo::findOrFail($id);
        $cargo->update($request->all());

        return redirect()->route('cargos.index')->with('success', 'Cargo actualizado exitosamente.');
    }

    // Eliminar un cargo
    public function destroy($id)
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
        $cargo = Cargo::findOrFail($id);
        // Verifica si el cargo está referenciado en personas
        if (\App\Models\Persona::where('cargo_id', $cargo->id_cargo)->exists()) {
            return redirect()->route('cargos.index')
                ->with('error', 'No se puede eliminar este estado porque está referenciado en otras tablas.');
        }

        $cargo->delete();
        return redirect()->route('cargos.index')->with('success', 'Cargo eliminado exitosamente.');
    }
}
