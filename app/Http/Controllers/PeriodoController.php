<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function index()
    {
        $periodos = Periodo::all();
        return view('periodos.index', compact('periodos'));
    }

    public function create()
    {
        return view('periodos.create');
    }

    public function store(Request $request)
    {
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
        $periodo = Periodo::findOrFail($id);
        return view('periodos.edit', compact('periodo'));
    }

    public function update(Request $request, $id)
    {
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
