<?php

namespace App\Http\Controllers;

use App\Models\Titulacion;
use App\Models\Periodo;
use App\Models\EstadoTitulacion;
use App\Models\Persona;
use App\Models\ResTema;
use App\Models\Resolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;



class TitulacionController extends Controller
{
    public function index(Request $request)
    {
        $estados = \App\Models\EstadoTitulacion::orderBy('nombre_estado')->get();

        $query = Titulacion::with([
            'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona'
        ]);

        if ($request->filled('director_filtro')) {
            $query->where('cedula_director', $request->director_filtro);
        }
        if ($request->filled('asesor1_filtro')) {
            $query->where('cedula_asesor1', $request->asesor1_filtro);
        }
        if ($request->filled('periodo_filtro')) {
            $query->where('periodo_id', $request->periodo_filtro);
        }
        if ($request->filled('estado_filtro')) {
            $query->where('estado_id', $request->estado_filtro);
        }
        if ($request->filled('fecha_inicio')) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        if ($request->filled('fecha_fin')) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }

        // Nueva lógica para filtrar por fecha en resoluciones
        if ($request->filled('fecha_inicio') || $request->filled('fecha_fin')) {
            $query->whereHas('resTemas.resolucion', function ($q) use ($request) {
                $q->whereHas('tipoResolucion', function ($q2) {
                    $q2->whereRaw('LOWER(nombre_tipo_res) = ?', ['consejo directivo']);
                });
                if ($request->filled('fecha_inicio')) {
                    $q->whereDate('fecha_res', '>=', $request->fecha_inicio);
                }
                if ($request->filled('fecha_fin')) {
                    $q->whereDate('fecha_res', '<=', $request->fecha_fin);
                }
            });
        }

        $titulaciones = $query->get();

        $docentes = \App\Models\Persona::whereHas('cargo', function($q) {
            $q->where('nombre_cargo', 'Docente');
        })->orderBy('nombres')->get();

        $periodos = \App\Models\Periodo::orderBy('periodo_academico')->get();

        return view('titulaciones.index', compact('titulaciones', 'docentes', 'periodos', 'estados'));
    }

    public function create()
    {
        $personas = Persona::with('cargo')->get(); 
        $periodos = Periodo::all();
        $estados = EstadoTitulacion::all();
        $resolucionesSeleccionadas = \App\Models\Resolucion::whereIn(
            'id_Reso',
            \App\Models\ResolucionSeleccionada::pluck('resolucion_id')
        )->get();

        $docentes = \App\Models\Persona::whereHas('cargo', function($q) {
            $q->where('nombre_cargo', 'Docente');
        })->orderBy('nombres')->get();

        return view('titulaciones.create', compact('periodos', 'estados', 'resolucionesSeleccionadas','personas', 'docentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tema' => 'required|string',
            'cedula_estudiante' => 'required|exists:personas,cedula',
            'cedula_director' => 'required|exists:personas,cedula',
            'cedula_asesor1' => 'required|exists:personas,cedula',
            'periodo_id' => 'required|exists:periodos,id_periodo',
            'estado_id' => 'required|exists:estado_titulaciones,id_estado',
            'avance' => 'required|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
            'acta_grado' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $data = $request->only([
            'tema',
            'cedula_estudiante',
            'cedula_director',
            'cedula_asesor1',
            'periodo_id',
            'estado_id',
            'avance',
            'observaciones'
        ]);

        // Si subió acta y el estado es Graduado, guárdala
        if (
            $request->hasFile('acta_grado') &&
            $request->file('acta_grado')->isValid() &&
            \App\Models\EstadoTitulacion::find($request->estado_id)?->nombre_estado === 'Graduado'
        ) {
            $data['acta_grado'] = $request->file('acta_grado')->store('actas_grado', 'public');
        }

        $titulacion = Titulacion::create($data);

        $resolucionesSeleccionadas = \App\Models\ResolucionSeleccionada::pluck('resolucion_id');
        foreach ($resolucionesSeleccionadas as $resolucion_id) {
            ResTema::create([
                'titulacion_id' => $titulacion->id_titulacion,
                'resolucion_id' => $resolucion_id,
                'tema' => $request->tema,
            ]);
        }

        return redirect()->route('titulaciones.index')->with('success', 'Titulación creada exitosamente.');
    }

    // public function edit($id)
    // {
    //     $titulacion = Titulacion::findOrFail($id);
    //     $periodos = \App\Models\Periodo::all();
    //     $estados = \App\Models\EstadoTitulacion::all();
    //     $personas = \App\Models\Persona::with('cargo')->get();

    //     // Buscar los IDs de las personas según la cédula almacenada
    //     $personaEstudiante = $personas->firstWhere('cedula', $titulacion->cedula_estudiante);
    //     $personaDirector = $personas->firstWhere('cedula', $titulacion->cedula_director);
    //     $personaAsesor = $personas->firstWhere('cedula', $titulacion->cedula_asesor1);

    //     return view('titulaciones.edit', compact(
    //         'titulacion', 'periodos', 'estados', 'personas',
    //         'personaEstudiante', 'personaDirector', 'personaAsesor'
    //     ));
    // }

 
public function edit($id)
{
    $titulacion = Titulacion::findOrFail($id);
    $periodos = \App\Models\Periodo::all();
    $estados = \App\Models\EstadoTitulacion::all();
    $personas = \App\Models\Persona::with('cargo')->get();

    // Buscar los IDs de las personas según la cédula almacenada
    $personaEstudiante = $personas->firstWhere('cedula', $titulacion->cedula_estudiante);
    $personaDirector = $personas->firstWhere('cedula', $titulacion->cedula_director);
    $personaAsesor = $personas->firstWhere('cedula', $titulacion->cedula_asesor1);

    return view('titulaciones.edit', compact(
        'titulacion', 'periodos', 'estados', 'personas',
        'personaEstudiante', 'personaDirector', 'personaAsesor'
    ));
}

    public function update(Request $request, $id)
    {
        $request->validate([
            'tema' => 'required|string',
            'cedula_estudiante' => 'required|exists:personas,cedula',
            'cedula_director' => 'required|exists:personas,cedula',
            'cedula_asesor1' => 'required|exists:personas,cedula',
            'periodo_id' => 'required|exists:periodos,id_periodo',
            'estado_id' => 'required|exists:estado_titulaciones,id_estado',
            'avance' => 'required|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
            'acta_grado' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $titulacion = Titulacion::findOrFail($id);

        $data = $request->only([
            'tema',
            'cedula_estudiante',
            'cedula_director',
            'cedula_asesor1',
            'periodo_id',
            'estado_id',
            'avance',
            'observaciones'
        ]);

        // Solo guardar acta de grado si el estado es Graduado y se subió archivo
        $estadoGraduado = EstadoTitulacion::find($request->estado_id)?->nombre_estado === 'Graduado';

        if ($estadoGraduado && $request->hasFile('acta_grado') && $request->file('acta_grado')->isValid()) {
            // Borra el archivo anterior si existe
            if ($titulacion->acta_grado) {
                Storage::disk('public')->delete($titulacion->acta_grado);
            }
            $data['acta_grado'] = $request->file('acta_grado')->store('actas_grado', 'public');
        }

        // Si el estado ya no es Graduado, elimina el acta de grado
        if (!$estadoGraduado && $titulacion->acta_grado) {
            Storage::disk('public')->delete($titulacion->acta_grado);
            $data['acta_grado'] = null;
        }

        $titulacion->update($data);

        return redirect()->route('titulaciones.index')->with('success', 'Titulación actualizada correctamente.');
    }

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');

        // Detecta delimitador automáticamente
        $firstLine = fgets($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($handle);

        $header = fgetcsv($handle, 0, $delimiter);

        // Elimina BOM del primer encabezado si existe
        if (isset($header[0])) {
            $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
        }

        // Mapeo robusto
        $map = [
            'tema' => 'tema',
            'cedulaestudiante' => 'cedula_estudiante',
            'cedula estudiante' => 'cedula_estudiante',
            'cédulaestudiante' => 'cedula_estudiante',
            'cédula estudiante' => 'cedula_estudiante',
            'ceduladirector' => 'cedula_director',
            'cedula director' => 'cedula_director',
            'céduladirector' => 'cedula_director',
            'cédula director' => 'cedula_director',
            'cedulaasesor1' => 'cedula_asesor1',
            'cedula asesor1' => 'cedula_asesor1',
            'cedulaasesor 1' => 'cedula_asesor1',
            'cedula asesor 1' => 'cedula_asesor1',
            'cédulaasesor1' => 'cedula_asesor1',
            'cédula asesor1' => 'cedula_asesor1',
            'cédulaasesor 1' => 'cedula_asesor1',
            'cédula asesor 1' => 'cedula_asesor1',
            'periodo' => 'periodo',
            'estado' => 'estado',
            'avance' => 'avance',
            'observaciones' => 'observaciones',
        ];

        // Normaliza encabezados y mapea a campos de base de datos
        $normalize = function($string) {
            $string = mb_strtolower($string, 'UTF-8');
            $string = preg_replace('/[áàäâ]/u', 'a', $string);
            $string = preg_replace('/[éèëê]/u', 'e', $string);
            $string = preg_replace('/[íìïî]/u', 'i', $string);
            $string = preg_replace('/[óòöô]/u', 'o', $string);
            $string = preg_replace('/[úùüû]/u', 'u', $string);
            $string = preg_replace('/[ñ]/u', 'n', $string);
            $string = preg_replace('/\s+/', '', $string); // quita todos los espacios
            return $string;
        };

        $normalizedHeader = [];
        foreach ($header as $h) {
            $key = $normalize($h);
            $normalizedHeader[] = $map[$key] ?? $key;
        }

        $importados = 0;
        $saltados = 0;
        $errores = [];

        $resolucionesSeleccionadas = \App\Models\ResolucionSeleccionada::pluck('resolucion_id');

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($row) !== count($normalizedHeader)) {
                $saltados++;
                $errores[] = "Fila con columnas incorrectas: " . implode($delimiter, $row);
                continue;
            }

            $data = array_combine($normalizedHeader, $row);

            // Limpia cédulas de espacios y tabulaciones
            $data['cedula_estudiante'] = trim(preg_replace('/\s+/', '', $data['cedula_estudiante']));
            $data['cedula_director'] = trim(preg_replace('/\s+/', '', $data['cedula_director']));
            $data['cedula_asesor1'] = trim(preg_replace('/\s+/', '', $data['cedula_asesor1']));

            // Busca periodo y estado por nombre (insensible a mayúsculas/minúsculas)
            $periodo = \App\Models\Periodo::whereRaw('LOWER(periodo_academico) = ?', [strtolower(trim($data['periodo']))])->first();
            $estado = \App\Models\EstadoTitulacion::whereRaw('LOWER(nombre_estado) = ?', [strtolower(trim($data['estado']))])->first();

            if (
                $periodo &&
                $estado &&
                \App\Models\Persona::where('cedula', $data['cedula_estudiante'])->exists() &&
                \App\Models\Persona::where('cedula', $data['cedula_director'])->exists() &&
                \App\Models\Persona::where('cedula', $data['cedula_asesor1'])->exists()
            ) {
                $titulacion = \App\Models\Titulacion::create([
                    'tema' => $data['tema'],
                    'cedula_estudiante' => $data['cedula_estudiante'],
                    'cedula_director' => $data['cedula_director'],
                    'cedula_asesor1' => $data['cedula_asesor1'],
                    'periodo_id' => $periodo->id_periodo,
                    'estado_id' => $estado->id_estado,
                    'avance' => $data['avance'],
                    'observaciones' => $data['observaciones'] ?? null,
                ]);

                foreach ($resolucionesSeleccionadas as $resolucion_id) {
                    \App\Models\ResTema::create([
                        'titulacion_id' => $titulacion->id_titulacion,
                        'resolucion_id' => $resolucion_id,
                        'tema' => $data['tema'],
                    ]);
                }
                $importados++;
            } else {
                $saltados++;
                $errores[] = "Datos no válidos en fila: " . implode($delimiter, $row);
            }
        }
        fclose($handle);

        return redirect()->route('titulaciones.index')->with('success', "Titulaciones importadas: $importados. Filas saltadas: $saltados. " . implode(' | ', $errores));
    }

    public function destroy($id)
    {
        $titulacion = Titulacion::findOrFail($id);
        $titulacion->delete();
        return redirect()->route('titulaciones.index')->with('success', 'Titulación eliminada correctamente.');
    }

    public function show($id)
    {
        $titulacion = Titulacion::with([
            'estudiantePersona', 'directorPersona', 'asesor1Persona',
            'periodo', 'estado', 'resTemas.resolucion.tipoResolucion'
        ])->findOrFail($id);

        return view('titulaciones.show', compact('titulacion'));
    }

    public function pdf(Request $request)
    {
        // Repite la lógica de filtros del index
        $query = Titulacion::with([
            'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
        ]);

        if ($request->filled('director_filtro')) {
            $query->where('cedula_director', $request->director_filtro);
        }
        if ($request->filled('asesor1_filtro')) {
            $query->where('cedula_asesor1', $request->asesor1_filtro);
        }
        if ($request->filled('periodo_filtro')) {
            $query->where('periodo_id', $request->periodo_filtro);
        }
        if ($request->filled('estado_filtro')) {
            $query->where('estado_id', $request->estado_filtro);
        }
        if ($request->filled('fecha_inicio') || $request->filled('fecha_fin')) {
            $query->whereHas('resTemas.resolucion', function ($q) use ($request) {
                $q->whereHas('tipoResolucion', function ($q2) {
                    $q2->whereRaw('LOWER(nombre_tipo_res) = ?', ['consejo directivo']);
                });
                if ($request->filled('fecha_inicio')) {
                    $q->whereDate('fecha_res', '>=', $request->fecha_inicio);
                }
                if ($request->filled('fecha_fin')) {
                    $q->whereDate('fecha_res', '<=', $request->fecha_fin);
                }
            });
        }

        $titulo = 'Reporte de Titulaciones';

        if ($request->filled('estado_filtro')) {
            $estado = \App\Models\EstadoTitulacion::find($request->estado_filtro);
            if ($estado) {
                $titulo = 'Reporte de titulaciones ' . strtolower($estado->nombre_estado);
            }
        } elseif ($request->filled('director_filtro')) {
            $director = \App\Models\Persona::where('cedula', $request->director_filtro)->first();
            if ($director) {
                $titulo = 'Reporte de titulaciones de ' . $director->nombres;
            }
        } elseif ($request->filled('asesor1_filtro')) {
            $asesor = \App\Models\Persona::where('cedula', $request->asesor1_filtro)->first();
            if ($asesor) {
                $titulo = 'Reporte de titulaciones asesoradas por ' . $asesor->nombres;
            }
        } elseif ($request->filled('periodo_filtro')) {
            $periodo = \App\Models\Periodo::find($request->periodo_filtro);
            if ($periodo) {
                $titulo = 'Reporte de titulaciones del periodo ' . $periodo->periodo_academico;
            }
        }

        $titulaciones = $query->get();

        $pdf = PDF::loadView('titulaciones.pdf', compact('titulaciones', 'titulo'));
        return $pdf->stream('titulaciones_filtradas.pdf');
    }
}
