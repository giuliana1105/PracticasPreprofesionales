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

class TitulacionController extends Controller
{
    public function index()
    {
        $titulaciones = Titulacion::with(['periodo', 'estado', 'resTemas.resolucion'])->get();
        return view('titulaciones.index', compact('titulaciones'));
    }

    public function create()
    {
        $periodos = Periodo::all();
        $estados = EstadoTitulacion::all();
        // Solo resoluciones seleccionadas
        $resolucionesSeleccionadas = \App\Models\Resolucion::whereIn(
            'id_Reso',
            \App\Models\ResolucionSeleccionada::pluck('resolucion_id')
        )->get();

        return view('titulaciones.create', compact('periodos', 'estados', 'resolucionesSeleccionadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tema' => 'required|string',
            'estudiante' => 'required|string',
            'cedula_estudiante' => 'required|exists:personas,cedula',
            'director' => 'required|string',
            'cedula_director' => 'required|exists:personas,cedula',
            'asesor1' => 'required|string',
            'cedula_asesor1' => 'required|exists:personas,cedula',
            'periodo_id' => 'required|exists:periodos,id_periodo',
            'estado_id' => 'required|exists:estado_titulaciones,id_estado',
            'avance' => 'required|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $titulacion = Titulacion::create($request->all());

        // Obtener todas las resoluciones seleccionadas
        $resolucionesSeleccionadas = \App\Models\ResolucionSeleccionada::pluck('resolucion_id');

        // Guardar relación en res_temas
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
        $titulacion = Titulacion::findOrFail($id);
        $periodos = Periodo::all();
        $estados = EstadoTitulacion::all();
        return view('titulaciones.edit', compact('titulacion', 'periodos', 'estados'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tema' => 'required|string',
            'estudiante' => 'required|string',
            'cedula_estudiante' => 'required|exists:personas,cedula',
            'director' => 'required|string',
            'cedula_director' => 'required|exists:personas,cedula',
            'asesor1' => 'required|string',
            'cedula_asesor1' => 'required|exists:personas,cedula',
            'periodo_id' => 'required|exists:periodos,id_periodo',
            'estado_id' => 'required|exists:estado_titulaciones,id_estado',
            'avance' => 'required|integer|min:0|max:100',
            'observaciones' => 'nullable|string',
        ]);

        $titulacion = Titulacion::findOrFail($id);
        $titulacion->update($request->all());

        return redirect()->route('titulaciones.index')->with('success', 'Titulación actualizada.');
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

        // Mapeo de encabezados del usuario a campos de la base de datos
        $map = [
            'tema' => 'tema',
            'estudiante' => 'estudiante',
            'cedulaestudiante' => 'cedula_estudiante',
            'cedulaestudiante' => 'cedula_estudiante',
            'cedula estudiante' => 'cedula_estudiante',
            'cédulaestudiante' => 'cedula_estudiante',
            'cédula estudiante' => 'cedula_estudiante',
            'director' => 'director',
            'ceduladirector' => 'cedula_director',
            'cedula director' => 'cedula_director',
            'céduladirector' => 'cedula_director',
            'cédula director' => 'cedula_director',
            'asesor1' => 'asesor1',
            'asesor 1' => 'asesor1',
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

        // Función para limpiar encabezados: minúsculas, sin espacios, sin tildes
        $normalize = function($string) {
            $string = mb_strtolower($string, 'UTF-8');
            $string = preg_replace('/[áàäâ]/u', 'a', $string);
            $string = preg_replace('/[éèëê]/u', 'e', $string);
            $string = preg_replace('/[íìïî]/u', 'i', $string);
            $string = preg_replace('/[óòöô]/u', 'o', $string);
            $string = preg_replace('/[úùüû]/u', 'u', $string);
            $string = preg_replace('/[ñ]/u', 'n', $string);
            $string = preg_replace('/[^a-z0-9]/u', '', $string); // quita espacios y caracteres especiales
            return $string;
        };

        // Normaliza encabezados y mapea a campos de base de datos
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
                    'estudiante' => $data['estudiante'],
                    'cedula_estudiante' => $data['cedula_estudiante'],
                    'director' => $data['director'],
                    'cedula_director' => $data['cedula_director'],
                    'asesor1' => $data['asesor1'],
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

        $requeridos = [
            'tema','estudiante','cedula_estudiante','director','cedula_director',
            'asesor1','cedula_asesor1','periodo','estado','avance','observaciones'
        ];
        $faltantes = array_diff($requeridos, $normalizedHeader);
        if (count($faltantes)) {
            return redirect()->route('titulaciones.index')
                ->with('error', 'Faltan columnas en el CSV: ' . implode(', ', $faltantes));
        }

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
        // Puedes dejarlo vacío o redirigir a index
        return redirect()->route('resoluciones.index');
    }
}
