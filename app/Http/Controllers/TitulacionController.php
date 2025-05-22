<?php

namespace App\Http\Controllers;

use App\Models\Titulacion;
use App\Models\Resolucion;
use App\Models\Periodo;
use App\Models\Persona;
use App\Models\EstadoTitulacion;
use Illuminate\Http\Request;
use App\Models\Tema;
use App\Models\ResolucionSeleccionada;
use Illuminate\Support\Facades\Storage;

class TitulacionController extends Controller
{
    public function index()
    {
        $titulaciones = Titulacion::with([
            'tema',
            'estudiante',
            'docente',
            'asesor1',
            'asesor2',
            'periodo',
            'estado'
        ])->get();

        return view('titulaciones.index', compact('titulaciones'));
    }

    public function create()
    {
        // Obtener las resoluciones seleccionadas desde la tabla `resoluciones_seleccionadas`
        $resolucionesSeleccionadas = ResolucionSeleccionada::with('resolucion')->get()->pluck('resolucion');

        // Obtener los temas relacionados con las resoluciones seleccionadas
        $temas = Tema::whereHas('resoluciones', function ($query) use ($resolucionesSeleccionadas) {
            $query->whereIn('id_Reso', $resolucionesSeleccionadas->pluck('id_Reso'));
        })->get();

        // Depurar los temas obtenidos
       // dd($temas);

        // Obtener solo personas con cargo 'Estudiante'
        $estudiantes = Persona::whereHas('cargo', function($query) {
            $query->where('nombre_cargo', 'Estudiante');
        })->get();
        // Obtener solo personas con cargo 'Docente' para docente, asesor1 y asesor2
        $docentes = Persona::whereHas('cargo', function($query) {
            $query->where('nombre_cargo', 'Docente');
        })->get();
        $periodos = Periodo::all();
        $estados = EstadoTitulacion::all();
       // dd($periodos, $estados);

        // Pasar los datos a la vista
        return view('titulaciones.create', compact('resolucionesSeleccionadas', 'temas', 'estudiantes', 'docentes', 'periodos', 'estados'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'tema' => 'required|exists:temas,id_tema',
            'estudiante' => 'required|exists:personas,id',
            'docente' => 'required|exists:personas,id',
            'asesor1' => 'required|exists:personas,id',
            'asesor2' => 'nullable|exists:personas,id',
            'periodo' => 'required|exists:periodos,id_periodo',
            'estado' => 'required|exists:estado_titulaciones,id_estado',
            'avance' => 'required|numeric|min:0|max:100',
            'acta_de_grado' => 'nullable|file|mimes:pdf|max:2048',
            'observaciones' => 'nullable|string',
        ]);

        try {
            // Crear una nueva titulación
            $titulacion = new Titulacion();
            $titulacion->tema_id = $request->input('tema');
            $titulacion->estudiante_id = $request->input('estudiante');
            $titulacion->docente_id = $request->input('docente');
            $titulacion->asesor1_id = $request->input('asesor1');
            $titulacion->asesor2_id = $request->input('asesor2');
            $titulacion->periodo_id = $request->input('periodo');
            $titulacion->estado_id = $request->input('estado');
            $titulacion->avance = $request->input('avance');
            $titulacion->observaciones = $request->input('observaciones');

            // Guardar el archivo de acta de grado si se subió
            if ($request->hasFile('acta_de_grado')) {
                $titulacion->acta_de_grado = $request->file('acta_de_grado')->store('actas', 'public');
            }
            // Guardar la titulación en la base de datos
            $titulacion->save();

            // Redirigir al índice de titulaciones con un mensaje de éxito
            return redirect()->route('titulaciones.index')->with('success', 'Titulación creada exitosamente.');
        } catch (\Exception $e) {
            // Capturar errores y mostrar un mensaje
            return back()->with('error', 'Ocurrió un error al guardar la titulación: ' . $e->getMessage());
        }
    }

    public function edit(Titulacion $titulacion)
    {
        $estudiantes = \App\Models\Persona::whereHas('cargo', function($query) {
            $query->where('nombre_cargo', 'Estudiante');
        })->get();

        $docentes = \App\Models\Persona::whereHas('cargo', function($query) {
            $query->where('nombre_cargo', 'Docente');
        })->get();

        $periodos = \App\Models\Periodo::all();
        $estados = \App\Models\EstadoTitulacion::all();
        
        $temas = Tema::with('resoluciones')->get();

        return view('titulaciones.edit', compact('titulacion', 'estudiantes', 'docentes', 'periodos', 'estados','temas'));
    }
public function update(Request $request, Titulacion $titulacion)
{
    // Validación de datos
    $request->validate([
        'tema' => 'required',
        'estudiante' => 'required|exists:personas,id',
        'docente' => 'required|exists:personas,id',
        'asesor1' => 'required|exists:personas,id',
        'asesor2' => 'nullable|exists:personas,id',
        'periodo' => 'required|exists:periodos,id_periodo',
        'estado' => 'required|exists:estado_titulaciones,id_estado',
        'acta_de_grado' => 'nullable|mimes:pdf|max:2048',
        'observaciones' => 'nullable|string',
        'avance' => 'required|numeric',  // Cambiado a numeric para aceptar decimales si es necesario
    ]);

    // Asignar solo los campos permitidos
    $titulacion->tema_id = $request->input('tema');
    $titulacion->estudiante_id = $request->input('estudiante');
    $titulacion->docente_id = $request->input('docente');
    $titulacion->asesor1_id = $request->input('asesor1');
    $titulacion->asesor2_id = $request->input('asesor2');
    $titulacion->periodo_id = $request->input('periodo');
    $titulacion->estado_id = $request->input('estado');
    $titulacion->avance = $request->input('avance');
    $titulacion->observaciones = $request->input('observaciones');

    // Manejo del archivo PDF (solo si suben uno nuevo)
    if ($request->hasFile('acta_de_grado')) {
        // Eliminar el archivo anterior si existe
        if ($titulacion->acta_de_grado) {
            Storage::delete('public/actas/' . $titulacion->acta_de_grado);
        }

        // Guardar el nuevo archivo
        $titulacion->acta_de_grado = $request->file('acta_de_grado')->store('actas', 'public');
    }

    // Guardar los cambios
    $titulacion->save();

    // Redirigir a la lista de titulaciones con un mensaje de éxito
    return redirect()->route('titulaciones.index')->with('success', 'Titulación actualizada exitosamente.');
}

    // public function update(Request $request, Titulacion $titulacion)
    // {
    //     $request->validate([
    //         'tema' => 'required',
    //         'estudiante' => 'required|exists:personas,id',
    //         'docente' => 'required|exists:personas,id',
    //         'asesor1' => 'required|exists:personas,id',
    //         'asesor2' => 'nullable|exists:personas,id',
    //         'periodo' => 'required|exists:periodos,id_periodo',
    //         'estado' => 'required|exists:estado_titulaciones,id_estado',
    //         'acta_de_grado' => 'nullable|mimes:pdf|max:2048',
    //         'observaciones' => 'nullable|string',
    //         'avance' => 'required|integer',
    //     ]);

    //     // Asignar solo los campos permitidos
    //     $titulacion->tema_id = $request->input('tema');
    //     $titulacion->estudiante_id = $request->input('estudiante');
    //     $titulacion->docente_id = $request->input('docente');
    //     $titulacion->asesor1_id = $request->input('asesor1');
    //     $titulacion->asesor2_id = $request->input('asesor2');
    //     $titulacion->periodo_id = $request->input('periodo');
    //     $titulacion->estado_id = $request->input('estado');
    //     $titulacion->avance = $request->input('avance');
    //     $titulacion->observaciones = $request->input('observaciones');

    //     // Manejo del archivo PDF (solo si suben uno nuevo)
    //     if ($request->hasFile('acta_de_grado')) {
    //         $titulacion->acta_de_grado = $request->file('acta_de_grado')->store('actas', 'public');
    //     }

    //     $titulacion->save();

    //     return redirect()->route('titulaciones.index')->with('success', 'Titulación actualizada exitosamente.');
    // }

    public function destroy(Titulacion $titulacion)
    {
        $titulacion->delete();
        return redirect()->route('titulaciones.index')->with('success', 'Titulación eliminada exitosamente.');
    }
}
