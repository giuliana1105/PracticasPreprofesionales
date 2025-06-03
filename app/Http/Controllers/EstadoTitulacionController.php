<?php

namespace App\Http\Controllers;

use App\Models\EstadoTitulacion;
use Illuminate\Http\Request;

class EstadoTitulacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = EstadoTitulacion::all();
        return view('estado_titulaciones.index', compact('estados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('estado_titulaciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        return view('estado_titulaciones.show', compact('estadoTitulacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $estado = \App\Models\EstadoTitulacion::findOrFail($id);
        return view('estado_titulaciones.edit', compact('estado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
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