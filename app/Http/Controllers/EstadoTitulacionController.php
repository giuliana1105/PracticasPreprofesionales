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
    public function edit(EstadoTitulacion $estadoTitulacion)
    {
        return view('estado_titulaciones.edit', compact('estadoTitulacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EstadoTitulacion $estadoTitulacion)
    {
        $request->validate([
            'nombre_estado' => 'required|string|max:255|unique:estado_titulaciones,nombre_estado,'.$estadoTitulacion->id_estado.',id_estado'
        ]);

        $estadoTitulacion->update($request->all());

        return redirect()->route('estado-titulaciones.index')
                         ->with('success', 'Estado de titulación actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstadoTitulacion $estadoTitulacion)
    {
        $estadoTitulacion->delete();

        return redirect()->route('estado-titulaciones.index')
                         ->with('success', 'Estado de titulación eliminado exitosamente.');
    }
}