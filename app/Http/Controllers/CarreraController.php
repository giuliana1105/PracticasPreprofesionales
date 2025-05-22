<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    // Mostrar todas las carreras
    public function index()
    {
        $carreras = Carrera::all();
        return view('carreras.index', compact('carreras'));
    }

    // Mostrar el formulario para crear una nueva carrera
    public function create()
    {
        return view('carreras.create');
    }

    // Almacenar una nueva carrera
    public function store(Request $request)
    {
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
        return view('carreras.edit', compact('carrera'));
    }

    // Actualizar la carrera en la base de datos
    public function update(Request $request, Carrera $carrera)
    {
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
        $carrera->delete();
        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada exitosamente.');
    }
}
