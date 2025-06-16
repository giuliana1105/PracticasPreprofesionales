<?php

namespace App\Http\Controllers;

use App\Models\Titulacion;
use App\Models\Periodo;
use App\Models\EstadoTitulacion;
use App\Models\Persona;
use App\Models\ResTema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TitulacionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            $persona = $user->persona;
        } else {
            $persona = $user;
        }

        $esEstudiante = $persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante';
        $esDocente = $persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'docente';

        if ($esEstudiante) {
            // Solo puede ver sus propias titulaciones, sin filtros adicionales
            $titulaciones = Titulacion::with([
                'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
            ])->where('cedula_estudiante', $persona->cedula)->get();

            // Para la vista, pasa los arrays vacíos para filtros (no se mostrarán)
            $estados = collect();
            $docentes = collect();
            $periodos = collect();

            return view('titulaciones.index', compact('titulaciones', 'docentes', 'periodos', 'estados'));
        } elseif ($esDocente) {
            // DOCENTE: sólo ve titulaciones donde es director o asesor1
            $query = Titulacion::with([
                'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
            ])->where(function($q) use ($persona) {
                $q->where('cedula_director', $persona->cedula)
                  ->orWhere('cedula_asesor1', $persona->cedula);
            });

            // Filtros permitidos para docente
            if ($request->filled('busqueda')) {
                $busqueda = strtolower($request->input('busqueda'));
                $query->whereHas('estudiantePersona', function($q2) use ($busqueda) {
                    $q2->whereRaw('LOWER(nombres) LIKE ?', ['%' . $busqueda . '%']);
                });
            }
            if ($request->filled('estado_filtro')) {
                $query->where('estado_id', $request->estado_filtro);
            }
            if ($request->filled('periodo_filtro')) {
                $query->where('periodo_id', $request->periodo_filtro);
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

            $titulaciones = $query->get();

            $estados = \App\Models\EstadoTitulacion::orderBy('nombre_estado')->get();
            $periodos = \App\Models\Periodo::orderBy('periodo_academico')->get(); // <-- Asegúrate de cargar los periodos aquí
            $docentes = collect(); // No necesita filtro de docentes

            return view('titulaciones.index', compact('titulaciones', 'docentes', 'periodos', 'estados'));
        } else {
            // Usuario no estudiante: puede ver y filtrar todas las titulaciones
            $query = Titulacion::with([
                'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
            ]);

            // Filtros solo para usuarios no estudiantes
            if ($request->filled('busqueda')) {
                $busqueda = strtolower($request->input('busqueda'));
                $query->where(function($q) use ($busqueda) {
                    $q->whereRaw('LOWER(tema) LIKE ?', ['%' . $busqueda . '%'])
                        ->orWhereHas('estudiantePersona', function($q2) use ($busqueda) {
                            $q2->whereRaw('LOWER(nombres) LIKE ?', ['%' . $busqueda . '%']);
                        })
                        ->orWhereHas('directorPersona', function($q2) use ($busqueda) {
                            $q2->whereRaw('LOWER(nombres) LIKE ?', ['%' . $busqueda . '%']);
                        })
                        ->orWhereHas('asesor1Persona', function($q2) use ($busqueda) {
                            $q2->whereRaw('LOWER(nombres) LIKE ?', ['%' . $busqueda . '%']);
                        });
                });
            }

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

            $estados = \App\Models\EstadoTitulacion::orderBy('nombre_estado')->get();
            $docentes = \App\Models\Persona::whereHas('cargo', function($q) {
                $q->where('nombre_cargo', 'Docente');
            })->orderBy('nombres')->get();
            $periodos = \App\Models\Periodo::orderBy('periodo_academico')->get();

            return view('titulaciones.index', compact('titulaciones', 'docentes', 'periodos', 'estados'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['estudiante', 'docente', 'coordinador', 'decano'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
        
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
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['estudiante', 'docente', 'coordinador', 'decano'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
        
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

        $resolucionesSeleccionadas = \App\Models\ResolucionSeleccionada::pluck('resolucion_id');

        // VALIDACIÓN: No permitir crear si no hay resoluciones seleccionadas
        if ($resolucionesSeleccionadas->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Debe seleccionar resoluciones para poder crear una titulación.');
        }

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

        // Busca los nombres por cédula
        $estudiante = \App\Models\Persona::where('cedula', $data['cedula_estudiante'])->first();
        $director = \App\Models\Persona::where('cedula', $data['cedula_director'])->first();
        $asesor1 = \App\Models\Persona::where('cedula', $data['cedula_asesor1'])->first();

        $data['estudiante'] = $estudiante ? ($estudiante->nombres . ' ' . $estudiante->apellidos) : null;
        $data['director'] = $director ? ($director->nombres . ' ' . $director->apellidos) : null;
        $data['asesor1'] = $asesor1 ? ($asesor1->nombres . ' ' . $asesor1->apellidos) : null;

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
    $user = Auth::user();
    $persona = $user instanceof \App\Models\User ? $user->persona : $user;
    $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
    if (in_array($cargo, ['estudiante', 'coordinador', 'decano'])) {
        abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
    }
    $titulacion = Titulacion::findOrFail($id);
    $periodos = \App\Models\Periodo::all();
    $estados = \App\Models\EstadoTitulacion::all();
    $personas = \App\Models\Persona::with('cargo')->get();

    // Buscar los IDs de las personas según la cédula almacenada
    $personaEstudiante = $personas->firstWhere('cedula', $titulacion->cedula_estudiante);
    $personaDirector = $personas->firstWhere('cedula', $titulacion->cedula_director);
    $personaAsesor = $personas->firstWhere('cedula', $titulacion->cedula_asesor1);

    $esDocente = $persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'docente';

    return view('titulaciones.edit', compact(
        'titulacion', 'periodos', 'estados', 'personas',
        'personaEstudiante', 'personaDirector', 'personaAsesor', 'esDocente'
    ));
}

    public function update(Request $request, $id)
    {
     $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    $esDocente = $persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'docente';

    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
    }

    if ($esDocente) {
        $request->validate([
            'avance' => 'required|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
        ]);
        $titulacion = Titulacion::findOrFail($id);
        $titulacion->update([
            'avance' => $request->avance,
            'observaciones' => $request->observaciones,
        ]);
        return redirect()->route('titulaciones.index')->with('success', 'Titulación actualizada correctamente.');
    }

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
    {   $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && (strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante' || strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'docente')) {
        abort(403, 'No autorizado');
    }
    $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        // Verifica si hay resoluciones seleccionadas
        $resolucionesSeleccionadas = \App\Models\ResolucionSeleccionada::pluck('resolucion_id');
        if ($resolucionesSeleccionadas->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Debe seleccionar resoluciones para poder importar titulaciones desde CSV.');
        }

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

        $filaActual = 1; // Para numerar filas (considerando encabezado)
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $filaActual++;
            $erroresFila = [];

            if (count($row) !== count($normalizedHeader)) {
                $saltados++;
                $errores[] = "Fila {$filaActual}: columnas incorrectas (" . implode($delimiter, $row) . ")";
                continue;
            }

            $data = array_combine($normalizedHeader, $row);

            // Limpia cédulas de espacios y tabulaciones
            $data['cedula_estudiante'] = trim(preg_replace('/\s+/', '', $data['cedula_estudiante']));
            $data['cedula_director'] = trim(preg_replace('/\s+/', '', $data['cedula_director']));
            $data['cedula_asesor1'] = trim(preg_replace('/\s+/', '', $data['cedula_asesor1']));

            // Busca periodo y estado por nombre (insensible a mayúsculas/minúsculas)
            $nombrePeriodo = preg_replace('/\s+/', ' ', trim($data['periodo'] ?? ''));
            $nombreEstado = preg_replace('/\s+/', ' ', trim($data['estado'] ?? ''));

            $periodo = \App\Models\Periodo::whereRaw('LOWER(TRIM(periodo_academico)) = ?', [strtolower($nombrePeriodo)])->first();
            if (!$periodo) {
                $erroresFila[] = "Período '{$nombrePeriodo}' no registrado";
            }

            $estado = \App\Models\EstadoTitulacion::whereRaw('LOWER(TRIM(nombre_estado)) = ?', [strtolower($nombreEstado)])->first();
            if (!$estado) {
                $erroresFila[] = "Estado '{$nombreEstado}' no registrado";
            }

            $estudiante = \App\Models\Persona::where('cedula', $data['cedula_estudiante'])->first();
            if (!$estudiante) {
                $erroresFila[] = "Estudiante con cédula '{$data['cedula_estudiante']}' no registrado";
            }

            $director = \App\Models\Persona::where('cedula', $data['cedula_director'])->first();
            if (!$director) {
                $erroresFila[] = "Director con cédula '{$data['cedula_director']}' no registrado";
            }

            $asesor1 = \App\Models\Persona::where('cedula', $data['cedula_asesor1'])->first();
            if (!$asesor1) {
                $erroresFila[] = "Asesor 1 con cédula '{$data['cedula_asesor1']}' no registrado";
            }

            // Si hay errores, agrega el mensaje detallado
            if (count($erroresFila) > 0) {
                $saltados++;
                $errores[] = "Fila {$filaActual}: " . implode(' | ', $erroresFila);
                continue;
            }

            $titulacion = \App\Models\Titulacion::create([
                'tema' => $data['tema'],
                'estudiante' => $estudiante->nombres . ' ' . $estudiante->apellidos,
                'cedula_estudiante' => $data['cedula_estudiante'],
                'director' => $director->nombres . ' ' . $director->apellidos,
                'cedula_director' => $data['cedula_director'],
                'asesor1' => $asesor1->nombres . ' ' . $asesor1->apellidos,
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
        }
        fclose($handle);

        return redirect()->route('titulaciones.index')->with('success', "Titulaciones importadas: $importados. Filas saltadas: $saltados. " . implode(' | ', $errores));
    }

    public function destroy($id)
    {   $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['estudiante', 'docente', 'coordinador', 'decano'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
        



        // $user = Auth::user();
        // $persona = $user->persona ?? \App\Models\Persona::where('correo', $user->email)->with('cargo')->first();
        // $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        // // Ahora también restringe a coordinador y decano
        // if (in_array($cargo, ['estudiante', 'docente', 'coordinador', 'decano'])) {
        //     abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        // }

        $titulacion = Titulacion::findOrFail($id);
        $titulacion->delete();
        return redirect()->route('titulaciones.index')->with('success', 'Titulación eliminada correctamente.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $persona = $user->persona ?? null;
        $titulacion = Titulacion::with([
            'estudiantePersona', 'directorPersona', 'asesor1Persona',
            'periodo', 'estado', 'resTemas.resolucion.tipoResolucion'
        ])->findOrFail($id);

        // Si es estudiante, solo puede ver su propia titulación
        if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
            if ($titulacion->cedula_estudiante !== $persona->cedula) {
                abort(403, 'No autorizado');
            }
        }

        return view('titulaciones.show', compact('titulacion'));
    }

    public function pdf(Request $request)
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
    
        // Repite la lógica de filtros del index
        $query = Titulacion::with([
            'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
        ]);

        $esDocente = $persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'docente';

        if ($esDocente) {
            $query->where(function($q) use ($persona) {
                $q->where('cedula_director', $persona->cedula)
                  ->orWhere('cedula_asesor1', $persona->cedula);
            });
            if ($request->filled('busqueda')) {
                $busqueda = strtolower($request->input('busqueda'));
                $query->whereHas('estudiantePersona', function($q2) use ($busqueda) {
                    $q2->whereRaw('LOWER(nombres) LIKE ?', ['%' . $busqueda . '%']);
                });
            }
            if ($request->filled('estado_filtro')) {
                $query->where('estado_id', $request->estado_filtro);
            }
            if ($request->filled('periodo_filtro')) {
                $query->where('periodo_id', $request->periodo_filtro);
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
        } else {
            // ...existing code para otros roles...
        }

        $titulo = 'Reporte de Titulaciones';
        $titulaciones = $query->get();

        $pdf = PDF::loadView('titulaciones.pdf', compact('titulaciones', 'titulo'));
        return $pdf->stream('titulaciones_filtradas.pdf');
    }
}


