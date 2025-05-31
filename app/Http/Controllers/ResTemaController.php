<?php

namespace App\Http\Controllers;

use App\Models\ResTema;
use App\Models\Titulacion;
use App\Models\Resolucion;
use Illuminate\Http\Request;

class ResTemaController extends Controller
{
    public function index()
    {
        $resTemas = ResTema::with(['titulacion', 'resolucion'])->get();
        return view('res_temas.index', compact('resTemas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulacion_id' => 'required|exists:titulaciones,id_titulacion',
            'resolucion_id' => 'required|exists:resoluciones,id_Reso',
            'tema' => 'required|string',
        ]);

        ResTema::create($request->all());

        return back()->with('success', 'Relación guardada correctamente.');
    }

    public function destroy($id)
    {
        $resTema = ResTema::findOrFail($id);
        $resTema->delete();

        return back()->with('success', 'Relación eliminada.');
    }
}
