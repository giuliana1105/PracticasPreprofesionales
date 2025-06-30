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
use App\Models\AvanceHistorial;
use App\Models\Carrera; // Asegúrate de importar el modelo

class TitulacionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        // Usar solo el campo string 'cargo'
        $cargo = strtolower(trim($persona->cargo ?? ''));

        $esEstudiante = $cargo === 'estudiante';
        $esDocente = $cargo === 'docente';

        if ($esEstudiante) {
            $titulaciones = Titulacion::with([
                'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
            ])->where('cedula_estudiante', $persona->cedula)->get();

            $estados = collect();
            $docentes = collect();
            $periodos = collect();

            return view('titulaciones.index', compact('titulaciones', 'docentes', 'periodos', 'estados'));
        } elseif ($esDocente) {
            $query = Titulacion::with([
                'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
            ])->where(function($q) use ($persona) {
                $q->where('cedula_director', $persona->cedula)
                  ->orWhere('cedula_asesor1', $persona->cedula);
            });

            if ($request->filled('busqueda')) {
                $busqueda = strtolower($request->input('busqueda'));
                $query->whereHas('estudiantePersona', function($q2) use ($busqueda) {
                    $q2->whereRaw('LOWER(nombres) LIKE ?', ['%' . $busqueda . '%'])
                       ->orWhereRaw('LOWER(apellidos) LIKE ?', ['%' . $busqueda . '%']);
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
            if ($request->filled('carrera_filtro')) {
                $carrerasFiltro = array_map('strtolower', (array) $request->input('carrera_filtro'));
                $query->whereHas('estudiantePersona.carreras', function($q) use ($carrerasFiltro) {
                    $q->whereRaw('LOWER(siglas_carrera) IN (' . implode(',', array_fill(0, count($carrerasFiltro), '?')) . ')', $carrerasFiltro);
                });
                // Para compatibilidad con personas que solo tienen una carrera (relación antigua)
                $query->orWhereHas('estudiantePersona.carrera', function($q) use ($carrerasFiltro) {
                    $q->whereRaw('LOWER(siglas_carrera) IN (' . implode(',', array_fill(0, count($carrerasFiltro), '?')) . ')', $carrerasFiltro);
                });
            }

            $titulaciones = $query->get();

            $estados = EstadoTitulacion::orderBy('nombre_estado')->get();
            $periodos = Periodo::orderBy('periodo_academico')->get();
            $docentes = collect();
            $carreras = Carrera::orderBy('siglas_carrera')->get();

            return view('titulaciones.index', compact('titulaciones', 'docentes', 'periodos', 'estados', 'carreras'));
        } else {
            $query = Titulacion::with([
                'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
            ]);

            if ($request->filled('busqueda')) {
                $busqueda = strtolower($request->input('busqueda'));
                $query->where(function($q) use ($busqueda) {
                    $q->whereRaw('LOWER(tema) LIKE ?', ['%' . $busqueda . '%'])
                        ->orWhereHas('estudiantePersona', function($q2) use ($busqueda) {
                            $q2->whereRaw('LOWER(nombres) LIKE ?', ['%' . $busqueda . '%'])
                               ->orWhereRaw('LOWER(apellidos) LIKE ?', ['%' . $busqueda . '%']);
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
            if ($request->filled('carrera_filtro')) {
                $carrerasFiltro = array_map('strtolower', (array) $request->input('carrera_filtro'));
                $query->whereHas('estudiantePersona.carreras', function($q) use ($carrerasFiltro) {
                    $q->whereRaw('LOWER(siglas_carrera) IN (' . implode(',', array_fill(0, count($carrerasFiltro), '?')) . ')', $carrerasFiltro);
                });
                // Para compatibilidad con personas que solo tienen una carrera (relación antigua)
                $query->orWhereHas('estudiantePersona.carrera', function($q) use ($carrerasFiltro) {
                    $q->whereRaw('LOWER(siglas_carrera) IN (' . implode(',', array_fill(0, count($carrerasFiltro), '?')) . ')', $carrerasFiltro);
                });
            }

            $titulaciones = $query->get();

            $estados = EstadoTitulacion::orderBy('nombre_estado')->get();
            $docentes = Persona::whereRaw("LOWER(cargo) = 'docente'")->orderBy('nombres')->get();
            $periodos = Periodo::orderBy('periodo_academico')->get();
            $carreras = Carrera::orderBy('siglas_carrera')->get();

            return view('titulaciones.index', compact('titulaciones', 'docentes', 'periodos', 'estados', 'carreras'));
        }
    }

    public function create()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['estudiante', 'docente', 'coordinador', 'decano'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $personas = Persona::all();
        $periodos = Periodo::all();
        $estados = EstadoTitulacion::all();
        $resolucionesSeleccionadas = \App\Models\Resolucion::whereIn(
            'id_Reso',
            \App\Models\ResolucionSeleccionada::pluck('resolucion_id')
        )->get();

        $docentes = Persona::whereRaw("LOWER(cargo) = 'docente'")->orderBy('nombres')->get();

        return view('titulaciones.create', compact('periodos', 'estados', 'resolucionesSeleccionadas','personas', 'docentes'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
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

        $estudiante = Persona::where('cedula', $data['cedula_estudiante'])->first();
        $director = Persona::where('cedula', $data['cedula_director'])->first();
        $asesor1 = Persona::where('cedula', $data['cedula_asesor1'])->first();

        $data['estudiante'] = $estudiante ? ($estudiante->nombres . ' ' . $estudiante->apellidos) : null;
        $data['director'] = $director ? ($director->nombres . ' ' . $director->apellidos) : null;
        $data['asesor1'] = $asesor1 ? ($asesor1->nombres . ' ' . $asesor1->apellidos) : null;

        if (
            $request->hasFile('acta_grado') &&
            $request->file('acta_grado')->isValid() &&
            EstadoTitulacion::find($request->estado_id)?->nombre_estado === 'Graduado'
        ) {
            $data['acta_grado'] = $request->file('acta_grado')->store('actas_grado', 'public');
        }

        $titulacion = Titulacion::create($data);

        foreach ($resolucionesSeleccionadas as $resolucion_id) {
            ResTema::create([
                'titulacion_id' => $titulacion->id_titulacion,
                'resolucion_id' => $resolucion_id,
                'tema' => $request->tema,
            ]);
        }

        return redirect()->route('titulaciones.index')->with('success', 'Titulación creada exitosamente.');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['estudiante', 'coordinador', 'decano'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
        $titulacion = Titulacion::findOrFail($id);
        $periodos = Periodo::all();
        $estados = EstadoTitulacion::all();
        $personas = Persona::all();

        $personaEstudiante = $personas->firstWhere('cedula', $titulacion->cedula_estudiante);
        $personaDirector = $personas->firstWhere('cedula', $titulacion->cedula_director);
        $personaAsesor = $personas->firstWhere('cedula', $titulacion->cedula_asesor1);

        $esDocente = $cargo === 'docente';

        return view('titulaciones.edit', compact(
            'titulacion', 'periodos', 'estados', 'personas',
            'personaEstudiante', 'personaDirector', 'personaAsesor', 'esDocente'
        ));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        $esDocente = $cargo === 'docente';

        if ($cargo === 'estudiante') {
            abort(403, 'No autorizado');
        }

        if ($esDocente) {
            $request->validate([
                'avance' => 'required|integer|min:0|max:100',
                'observaciones' => 'nullable|string',
            ]);
            $titulacion = Titulacion::findOrFail($id);

            $cambios = [];
            if ($titulacion->avance != $request->avance) {
                $cambios[] = [
                    'campo' => 'avance',
                    'valor_anterior' => $titulacion->avance,
                    'valor_nuevo' => $request->avance,
                ];
            }
            if ($titulacion->observaciones != $request->observaciones) {
                $cambios[] = [
                    'campo' => 'observaciones',
                    'valor_anterior' => $titulacion->observaciones,
                    'valor_nuevo' => $request->observaciones,
                ];
            }
            foreach ($cambios as $cambio) {
                AvanceHistorial::create([
                    'titulacion_id' => $titulacion->id_titulacion,
                    'docente_id' => $persona->id,
                    'campo' => $cambio['campo'],
                    'valor_anterior' => $cambio['valor_anterior'],
                    'valor_nuevo' => $cambio['valor_nuevo'],
                ]);
            }

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

        // --- CORRECCIÓN: comparación robusta para estado graduado ---
        $estadoGraduado = false;
        $estadoObj = EstadoTitulacion::find($request->estado_id);
        if ($estadoObj) {
            $estadoGraduado = strtolower(trim($estadoObj->nombre_estado)) === 'graduado';
        }

        if ($estadoGraduado && $request->hasFile('acta_grado') && $request->file('acta_grado')->isValid()) {
            if ($titulacion->acta_grado) {
                Storage::disk('public')->delete($titulacion->acta_grado);
            }
            $data['acta_grado'] = $request->file('acta_grado')->store('actas_grado', 'public');
        }

        if (!$estadoGraduado && $titulacion->acta_grado) {
            Storage::disk('public')->delete($titulacion->acta_grado);
            $data['acta_grado'] = null;
        }

        $titulacion->update($data);

        return redirect()->route('titulaciones.index')->with('success', 'Titulación actualizada correctamente.');
    }

    public function importCsv(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['estudiante', 'docente'])) {
            abort(403, 'No autorizado');
        }
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $resolucionesSeleccionadas = \App\Models\ResolucionSeleccionada::pluck('resolucion_id');
        if ($resolucionesSeleccionadas->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Debe seleccionar resoluciones para poder importar titulaciones desde CSV.');
        }

        $file = $request->file('csv_file');
        $handle = fopen($file, 'r');

        $firstLine = fgets($handle);
        $delimiter = (substr_count($firstLine, ';') > substr_count($firstLine, ',')) ? ';' : ',';
        rewind($handle);

        $header = fgetcsv($handle, 0, $delimiter);

        if (isset($header[0])) {
            $header[0] = preg_replace('/^\x{FEFF}/u', '', $header[0]);
        }

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

        $normalize = function($string) {
            $string = mb_strtolower($string, 'UTF-8');
            $string = preg_replace('/[áàäâ]/u', 'a', $string);
            $string = preg_replace('/[éèëê]/u', 'e', $string);
            $string = preg_replace('/[íìïî]/u', 'i', $string);
            $string = preg_replace('/[óòöô]/u', 'o', $string);
            $string = preg_replace('/[úùüû]/u', 'u', $string);
            $string = preg_replace('/[ñ]/u', 'n', $string);
            $string = preg_replace('/\s+/', '', $string);
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

        $filaActual = 1;
        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $filaActual++;
            $erroresFila = [];

            if (count($row) !== count($normalizedHeader)) {
                $saltados++;
                $errores[] = "Fila {$filaActual}: columnas incorrectas (" . implode($delimiter, $row) . ")";
                continue;
            }

            $data = array_combine($normalizedHeader, $row);

            $data['cedula_estudiante'] = trim(preg_replace('/\s+/', '', $data['cedula_estudiante']));
            $data['cedula_director'] = trim(preg_replace('/\s+/', '', $data['cedula_director']));
            $data['cedula_asesor1'] = trim(preg_replace('/\s+/', '', $data['cedula_asesor1']));

            $nombrePeriodo = preg_replace('/\s+/', ' ', trim($data['periodo'] ?? ''));
            $nombreEstado = preg_replace('/\s+/', ' ', trim($data['estado'] ?? ''));

            $periodo = Periodo::whereRaw('LOWER(TRIM(periodo_academico)) = ?', [strtolower($nombrePeriodo)])->first();
            if (!$periodo) {
                $erroresFila[] = "Período '{$nombrePeriodo}' no registrado";
            }

            $estado = EstadoTitulacion::whereRaw('LOWER(TRIM(nombre_estado)) = ?', [strtolower($nombreEstado)])->first();
            if (!$estado) {
                $erroresFila[] = "Estado '{$nombreEstado}' no registrado";
            }

            $estudiante = Persona::where('cedula', $data['cedula_estudiante'])->first();
            if (!$estudiante) {
                $erroresFila[] = "Estudiante con cédula '{$data['cedula_estudiante']}' no registrado";
            }

            $director = Persona::where('cedula', $data['cedula_director'])->first();
            if (!$director) {
                $erroresFila[] = "Director con cédula '{$data['cedula_director']}' no registrado";
            }

            $asesor1 = Persona::where('cedula', $data['cedula_asesor1'])->first();
            if (!$asesor1) {
                $erroresFila[] = "Asesor 1 con cédula '{$data['cedula_asesor1']}' no registrado";
            }

            if (count($erroresFila) > 0) {
                $saltados++;
                $errores[] = "Fila {$filaActual}: " . implode(' | ', $erroresFila);
                continue;
            }

            $titulacion = Titulacion::create([
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
                ResTema::create([
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
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['estudiante', 'docente', 'coordinador', 'decano'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $titulacion = Titulacion::findOrFail($id);
        $titulacion->delete();
        return redirect()->route('titulaciones.index')->with('success', 'Titulación eliminada correctamente.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));

        $titulacion = Titulacion::with([
            'estudiantePersona', 'directorPersona', 'asesor1Persona',
            'periodo', 'estado', 'resTemas.resolucion.tipoResolucion',
            'avanceHistorial.docente'
        ])->findOrFail($id);

        if ($cargo === 'estudiante') {
            if ($titulacion->cedula_estudiante !== $persona->cedula) {
                abort(403, 'No autorizado');
            }
        }

        return view('titulaciones.show', compact('titulacion'));
    }

    public function pdf(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? Persona::where('email', $user->email)->first() : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if ($cargo === 'estudiante') {
            abort(403, 'No autorizado');
        }

        $query = Titulacion::with([
            'periodo', 'estado', 'resTemas.resolucion', 'directorPersona', 'asesor1Persona', 'estudiantePersona'
        ]);

        $esDocente = $cargo === 'docente';

        if ($esDocente) {
            $query->where(function($q) use ($persona) {
                $q->where('cedula_director', $persona->cedula)
                  ->orWhere('cedula_asesor1', $persona->cedula);
            });
            if ($request->filled('busqueda')) {
                $busqueda = strtolower($request->input('busqueda'));
                $query->whereHas('estudiantePersona', function($q2) use ($busqueda) {
                    $q2->whereRaw('LOWER(CONCAT(nombres, " ", apellidos)) LIKE ?', ["%$busqueda%"])
                       ->orWhereRaw('LOWER(nombres) LIKE ?', ["%$busqueda%"])
                       ->orWhereRaw('LOWER(apellidos) LIKE ?', ["%$busqueda%"]);
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
        }

        $titulo = 'Reporte de Titulaciones';
        $titulaciones = $query->get();

        $pdf = PDF::loadView('titulaciones.pdf', compact('titulaciones', 'titulo'));
        return $pdf->stream('titulaciones_filtradas.pdf');
    }
}


