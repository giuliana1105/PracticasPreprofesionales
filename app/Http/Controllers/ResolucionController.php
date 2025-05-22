<?php

namespace App\Http\Controllers;

use App\Models\Resolucion;
use App\Models\Tema;
use App\Models\ResolucionTema;
use App\Models\TipoResolucion;
use App\Models\ResolucionSeleccionada;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResolucionController extends Controller
{
    public function index()
    {
        $resoluciones = Resolucion::with('tipoResolucion')
                     ->orderBy('fecha_res', 'desc')
                     ->paginate(10);

        // Obtener la URL del archivo para cada resolución
        foreach ($resoluciones as $resolucion) {
            $resolucion->archivo_url = $resolucion->archivo_pdf
                ? asset('storage/' . $resolucion->archivo_pdf)
                : null;
        }
        
        return view('resoluciones.index', compact('resoluciones'));
    }


    public function createTemas(Request $request)
    {
        $resolucionesIds = $request->input('resoluciones', []);
        $resolucionesSeleccionadas = Resolucion::whereIn('id_Reso', $resolucionesIds)->get();
        $temas = Tema::all();

        return view('temas.create', compact('resolucionesSeleccionadas', 'temas'));
    }

    // En ResolucionController.php
    public function procesarSeleccion(Request $request)
    {
        $request->validate([
            'resoluciones' => 'required|array|min:1',
        ]);

        // Almacena las resoluciones seleccionadas en la sesión o pasa como parámetro
        return redirect()->route('resoluciones.temas.create', [
            'resoluciones' => $request->input('resoluciones')
        ]);
    }

    public function create()
    {
        // Obtener los tipos de resolución para mostrarlos en el formulario
        $tipos = TipoResolucion::all();

        // Mostrar la vista para crear la resolución
        
        return view('resoluciones.create', compact('tipos'));
    }

    public function storeTemas(Request $request)
    {
        // Depurar los datos enviados desde el formulario
        dd($request->all());

        // Validar los datos
        $request->validate([
            'resoluciones' => 'required|string',
            'temas' => 'required|json'
        ]);

        // Procesar IDs de resoluciones
        $resolucionesIds = explode(',', $request->resoluciones);
        
        // Decodificar y validar JSON
        $temas = json_decode($request->temas, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['temas' => 'Formato de temas inválido']);
        }

        // Asociar temas a resoluciones
        foreach ($resolucionesIds as $resolucionId) {
            foreach ($temas as $temaData) {
                $tema = Tema::firstOrCreate(['nombre_tema' => $temaData['nombre_tema']]);
                
                ResolucionTema::firstOrCreate([
                    'resolucion_id' => $resolucionId,
                    'tema_id' => $tema->id_tema
                ]);
            }
        }

        return redirect()->route('resoluciones.index')->with('success', 'Temas asignados exitosamente.');
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'numero_res' => 'required|string|max:50',
            'fecha_res' => 'required|date_format:Y-m-d',
            'tipo_res' => 'required|exists:tipo_resoluciones,id_tipo_res',
            'archivo_pdf' => 'required|file|mimes:pdf|max:2048'
        ]);

        // Almacenar el archivo PDF
        $archivoPath = $request->file('archivo_pdf')->store('resoluciones', 'public');

        // Crear la nueva resolución
        Resolucion::create([
            'numero_res' => $request->numero_res,
            'fecha_res' => $request->fecha_res,
            'tipo_res' => $request->tipo_res,
            'archivo_pdf' => $archivoPath // Guardar la ruta relativa, ej: resoluciones/archivo.pdf
        ]);

        // Redirigir a la lista de resoluciones con un mensaje de éxito
        return redirect()->route('resoluciones.index')
                         ->with('success', 'Resolución creada exitosamente.');
    }

    public function seleccionarResoluciones(Request $request)
    {
        $resolucionesIds = $request->input('resoluciones', []);

        // Filtrar solo valores numéricos
        $resolucionesIds = array_filter($resolucionesIds, function($id) {
            return is_numeric($id);
        });

        if (empty($resolucionesIds)) {
            return redirect()->route('resoluciones.index')->with('error', 'Debe seleccionar al menos una resolución.');
        }

        ResolucionSeleccionada::truncate();

        foreach ($resolucionesIds as $id) {
            ResolucionSeleccionada::create(['resolucion_id' => $id]);
        }

        return redirect()->route('temas.create')->with('success', 'Resoluciones seleccionadas correctamente. Ahora puede ingresar los temas.');
    }

    public function cambiarResoluciones()
    {
        // Eliminar todas las resoluciones seleccionadas
        ResolucionSeleccionada::truncate();

        // Redirigir a la pantalla de selección de resoluciones
        return redirect()->route('resoluciones.index')->with('success', 'Resoluciones limpiadas. Seleccione nuevas resoluciones.');
    }

    public function destroy($id)
    {
        $resolucion = Resolucion::findOrFail($id);

        // Si tienes archivos asociados, puedes eliminarlos del storage si lo deseas:
        // Storage::delete('public/resoluciones/' . $resolucion->archivo_pdf);

        $resolucion->delete();

        return redirect()->route('resoluciones.index')->with('success', 'Resolución eliminada exitosamente.');
    }
}
