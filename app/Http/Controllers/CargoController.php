<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    // Mostrar todos los cargos
    public function index()
    {
        $cargos = Cargo::all();
        return view('cargos.index', compact('cargos'));
    }

    // Mostrar el formulario para crear un nuevo cargo
    public function create()
    {
        return view('cargos.create');
    }

    // Almacenar un nuevo cargo
    public function store(Request $request)
    {
        $request->validate([
            'nombre_cargo' => 'required|max:255',
            'siglas_cargo' => 'required|max:10',
        ]);

        Cargo::create($request->all());

        return redirect()->route('cargos.index')->with('success', 'Cargo creado exitosamente.');
    }

    // Mostrar el formulario para editar un cargo existente
    public function edit(Cargo $cargo)
    {
        return view('cargos.edit', compact('cargo'));
    }

    // Actualizar el cargo en la base de datos
    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'nombre_cargo' => 'required|max:255',
            'siglas_cargo' => 'required|max:10',
        ]);

        $cargo->update($request->all());

        return redirect()->route('cargos.index')->with('success', 'Cargo actualizado exitosamente.');
    }

    // Eliminar un cargo
    public function destroy(Cargo $cargo)
    {
        $cargo->delete();
        return redirect()->route('cargos.index')->with('success', 'Cargo eliminado exitosamente.');
    }
}
