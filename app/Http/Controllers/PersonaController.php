<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Carrera;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class PersonaController extends Controller
{
    // Mostrar todas las personas
    public function index()
    {
        $personas = Persona::with(['carrera', 'cargo'])->get();
        return view('personas.index', compact('personas'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $carreras = Carrera::all();
        $cargos = Cargo::all();
        return view('personas.create', compact('carreras', 'cargos'));
    }

    // Almacenar nueva persona
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cedula' => 'required|string|max:20|unique:personas,cedula',
            'nombres' => 'required|string|max:100',
            'celular' => 'required|string|max:15',
            'correo' => 'required|email|max:100|unique:personas,correo',
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'cargo_id' => 'required|exists:cargos,id_cargo',
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombres.required' => 'Los nombres son obligatorios',
            'celular.required' => 'El celular es obligatorio',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Ingrese un correo electrónico válido',
            'correo.unique' => 'Este correo electrónico ya está registrado',
            'carrera_id.required' => 'La carrera es obligatoria',
            'carrera_id.exists' => 'La carrera seleccionada no es válida',
            'cargo_id.required' => 'El cargo es obligatorio',
            'cargo_id.exists' => 'El cargo seleccionado no es válido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Persona::create($request->all());
            return redirect()->route('personas.index')
                ->with('success', 'Persona registrada exitosamente');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al registrar: ' . $e->getMessage());
        }
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        $carreras = Carrera::all();
        $cargos = Cargo::all();
        return view('personas.edit', compact('persona', 'carreras', 'cargos'));
    }

    // Actualizar persona existente
    public function update(Request $request, $id)
    {
        $persona = Persona::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cedula' => 'required|string|max:20|unique:personas,cedula,'.$id,
            'nombres' => 'required|string|max:100',
            'celular' => 'required|string|max:15',
            'correo' => 'required|email|max:100|unique:personas,correo,'.$id,
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'cargo_id' => 'required|exists:cargos,id_cargo',
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombres.required' => 'Los nombres son obligatorios',
            'celular.required' => 'El celular es obligatorio',
            'correo.required' => 'El correo electrónico es obligatorio',
            'correo.email' => 'Ingrese un correo electrónico válido',
            'correo.unique' => 'Este correo electrónico ya está registrado',
            'carrera_id.required' => 'La carrera es obligatoria',
            'carrera_id.exists' => 'La carrera seleccionada no es válida',
            'cargo_id.required' => 'El cargo es obligatorio',
            'cargo_id.exists' => 'El cargo seleccionado no es válido'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $persona->update($request->all());
            return redirect()->route('personas.index')
                ->with('success', 'Persona actualizada exitosamente');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    // Eliminar persona
    public function destroy($id)
    {
        try {
            $persona = Persona::findOrFail($id);
            $persona->delete();
            
            return redirect()->route('personas.index')
                ->with('success', 'Persona eliminada exitosamente');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    // Importar desde CSV
    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'archivo_csv' => 'required|file|mimes:csv,txt|max:2048',
    //     ]);

    //     $file = $request->file('archivo_csv');
    //     $csv = Reader::createFromPath($file->getPathname(), 'r');
    //     $csv->setHeaderOffset(0);

    //     DB::beginTransaction();
    //     $importedCount = 0;
    //     $errores = [];

    //     try {
    //         foreach ($csv->getRecords() as $index => $row) {
    //             $numeroFila = $index + 2; // Fila real en el CSV (1 para encabezado + 1 porque empieza en 0)
                
    //             // Validar campos requeridos
    //             if (empty($row['cedula'])) {
    //                 $errores[$numeroFila] = 'Cédula vacía';
    //                 continue;
    //             }

    //             if (empty($row['nombres'])) {
    //                 $errores[$numeroFila] = 'Nombres vacíos';
    //                 continue;
    //             }

    //             if (empty($row['celular'])) {
    //                 $errores[$numeroFila] = 'Celular vacío';
    //                 continue;
    //             }

    //             if (empty($row['correo'])) {
    //                 $errores[$numeroFila] = 'Correo vacío';
    //                 continue;
    //             }

    //             if (empty($row['carrera'])) {
    //                 $errores[$numeroFila] = 'Carrera vacía';
    //                 continue;
    //             }

    //             if (empty($row['cargo'])) {
    //                 $errores[$numeroFila] = 'Cargo vacío';
    //                 continue;
    //             }

    //             // Verificar si la cédula ya existe
    //             if (Persona::where('cedula', $row['cedula'])->exists()) {
    //                 $errores[$numeroFila] = 'Cédula ya registrada: ' . $row['cedula'];
    //                 continue;
    //             }

    //             // Buscar carrera (insensible a mayúsculas/minúsculas)
    //             $carrera = Carrera::whereRaw('LOWER(nombre_carrera) = ?', [strtolower(trim($row['carrera']))])->first();
    //             if (!$carrera) {
    //                 $errores[$numeroFila] = 'Carrera no encontrada: ' . $row['carrera'];
    //                 continue;
    //             }

    //             // Buscar cargo (insensible a mayúsculas/minúsculas)
    //             $cargo = Cargo::whereRaw('LOWER(nombre_cargo) = ?', [strtolower(trim($row['cargo']))])->first();
    //             if (!$cargo) {
    //                 $errores[$numeroFila] = 'Cargo no encontrado: ' . $row['cargo'];
    //                 continue;
    //             }

    //             // Validar correo único si está presente
    //             if (!empty($row['correo'])) {
    //                 if (Persona::where('correo', $row['correo'])->exists()) {
    //                     $errores[$numeroFila] = 'Correo ya registrado: ' . $row['correo'];
    //                     continue;
    //                 }
    //             }

    //             // Crear registro
    //             Persona::create([
    //                 'cedula' => $row['cedula'],
    //                 'nombres' => $row['nombres'],
    //                 'celular' => $row['celular'],
    //                 'correo' => $row['correo'],
    //                 'carrera_id' => $carrera->id_carrera,
    //                 'cargo_id' => $cargo->id_cargo,
    //             ]);

    //             $importedCount++;
    //         }

    //         DB::commit();

    //         return redirect()->route('personas.index')->with([
    //             'success' => "Se importaron {$importedCount} registros correctamente",
    //             'import_errors' => $errores
    //         ]);

    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Error en la importación: ' . $e->getMessage());
    //     }
    // }
    
public function import(Request $request)
{
    $request->validate([
        'archivo_csv' => 'required|file|mimes:csv,txt|max:2048',
    ]);

    $file = $request->file('archivo_csv');

    // Detecta delimitador
    $handle = fopen($file->getPathname(), 'r');
    $firstLine = fgets($handle);
    fclose($handle);
    $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

    $csv = Reader::createFromPath($file->getPathname(), 'r');
    $csv->setHeaderOffset(0);
    $csv->setDelimiter($delimiter);

    DB::beginTransaction();
    $importedCount = 0;
    $errores = [];

    try {
        foreach ($csv->getRecords() as $index => $row) {
            $fila = $index + 2;

            // Validar campos requeridos
            if (empty($row['cedula'])) {
                $errores[$fila] = 'Cédula vacía';
                continue;
            }
            if (empty($row['nombres'])) {
                $errores[$fila] = 'Nombres vacíos';
                continue;
            }
            if (empty($row['celular'])) {
                $errores[$fila] = 'Celular vacío';
                continue;
            }
            if (empty($row['correo'])) {
                $errores[$fila] = 'Correo vacío';
                continue;
            }
            if (empty($row['carrera'])) {
                $errores[$fila] = 'Carrera vacía';
                continue;
            }
            if (empty($row['cargo'])) {
                $errores[$fila] = 'Cargo vacío';
                continue;
            }

            // Verificar cédula única
            if (Persona::where('cedula', $row['cedula'])->exists()) {
                $errores[$fila] = 'El número de cédula "' . $row['cedula'] . '" ya está registrado y no se puede volver a registrar.';
                continue;
            }

            // Buscar carrera y cargo (ignorando mayúsculas/minúsculas)
            $carrera = \App\Models\Carrera::whereRaw('LOWER(nombre_carrera) = ?', [strtolower(trim($row['carrera']))])->first();
            if (! $carrera) {
                $errores[$fila] = 'Carrera no encontrada: ' . $row['carrera'];
                continue;
            }

            $cargo = \App\Models\Cargo::whereRaw('LOWER(nombre_cargo) = ?', [strtolower(trim($row['cargo']))])->first();
            if (! $cargo) {
                $errores[$fila] = 'Cargo no encontrado: ' . $row['cargo'];
                continue;
            }

            // Verificar correo único
            if (Persona::where('correo', $row['correo'])->exists()) {
                $errores[$fila] = 'Correo ya registrado: ' . $row['correo'];
                continue;
            }

            // Crear persona
            Persona::create([
                'cedula'     => $row['cedula'],
                'nombres'    => $row['nombres'],
                'celular'    => $row['celular'],
                'correo'     => $row['correo'],
                'carrera_id' => $carrera->id_carrera,
                'cargo_id'   => $cargo->id_cargo,
            ]);

            $importedCount++;
        }

        DB::commit();

        return redirect()->route('personas.index')->with([
            'success'       => "Se importaron {$importedCount} registros correctamente.",
            'import_errors' => $errores,
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error', 'Error en la importación: ' . $e->getMessage());
    }
}



}