<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Carrera;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class PersonaController extends Controller
{
    // Mostrar todas las personas
    public function index()
    {$user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
    }
       $personas = Persona::with(['carrera', 'cargo'])->get();
        return view('personas.index', compact('personas'));
    }

    // Mostrar formulario de creación
    public function create()
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
    
        $carreras = Carrera::all();
        $cargos = Cargo::all();
        return view('personas.create', compact('carreras', 'cargos'));
    }

    // Almacenar nueva persona
    public function store(Request $request)
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
    
        $validator = Validator::make($request->all(), [
            'cedula' => 'required|string|max:20|unique:personas,cedula',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'celular' => 'required|string|max:15',
            'correo' => 'required|email|max:100|unique:personas,correo',
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'cargo_id' => 'required|exists:cargos,id_cargo',
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombres.required' => 'Los nombres son obligatorios',
            'apellidos.required' => 'Los apellidos son obligatorios',
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
            $persona = Persona::create($request->all());

            // Crea el usuario automáticamente si no existe
            if (!User::where('email', $persona->correo)->exists()) {
                User::create([
                    'name' => $persona->nombres . ' ' . $persona->apellidos,
                    'email' => $persona->correo,
                    'password' => Hash::make($persona->cedula), // la contraseña será la cédula
                ]);
            }

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
        $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
    }
    
        $persona = Persona::findOrFail($id);
        $carreras = Carrera::all();
        $cargos = Cargo::all();
        return view('personas.edit', compact('persona', 'carreras', 'cargos'));
    }

    // Actualizar persona existente
    public function update(Request $request, $id)
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
    
        $persona = Persona::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cedula' => 'required|string|max:20|unique:personas,cedula,'.$id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'celular' => 'required|string|max:15',
            'correo' => 'required|email|max:100|unique:personas,correo,'.$id,
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'cargo_id' => 'required|exists:cargos,id_cargo',
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombres.required' => 'Los nombres son obligatorios',
            'apellidos.required' => 'Los apellidos son obligatorios',
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
        $user = Auth::user();
    if ($user instanceof \App\Models\User) {
        $persona = $user->persona;
    } else {
        $persona = $user;
    }
    if ($persona && strtolower(trim($persona->cargo->nombre_cargo ?? '')) === 'estudiante') {
        abort(403, 'No autorizado');
    }
    
        try {
            $persona = Persona::findOrFail($id);

            // Verifica si la persona está referenciada en otras tablas
            $referenciada = false;

            // Ejemplo: verifica si la persona es director, asesor o estudiante en titulaciones
            if (
                \App\Models\Titulacion::where('cedula_estudiante', $persona->cedula)->exists() ||
                \App\Models\Titulacion::where('cedula_director', $persona->cedula)->exists() ||
                \App\Models\Titulacion::where('cedula_asesor1', $persona->cedula)->exists()
            ) {
                $referenciada = true;
            }

            if ($referenciada) {
                return redirect()->route('personas.index')
                    ->with('error', 'No se puede eliminar este estado porque está referenciado en otras tablas.');
            }

            $persona->delete();

            return redirect()->route('personas.index')
                ->with('success', 'Persona eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
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
       $request->validate([
            'archivo_csv' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('archivo_csv');
        $handle = fopen($file->getPathname(), 'r');
        $firstLine = fgets($handle);
        fclose($handle);
        $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';

        $csv = \League\Csv\Reader::createFromPath($file->getPathname(), 'r');
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($delimiter);

        DB::beginTransaction();
        $importedCount = 0;
        $duplicados = [];
        $errores = [];

        try {
            foreach ($csv->getRecords() as $index => $row) {
                $fila = $index + 2;

                // Validar campos requeridos mínimos
                if (empty($row['cedula'])) {
                    $errores[$fila] = 'Cédula vacía';
                    continue;
                }
                if (empty($row['nombres'])) {
                    $errores[$fila] = 'Nombres vacíos';
                    continue;
                }
                if (empty($row['apellidos'])) {
                    $errores[$fila] = 'Apellidos vacíos';
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
                    $errores[$fila] = 'Cargo vacía';
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

                $correo = trim(str_replace(['"', "'", ' '], '', $row['correo']));
                $cedula = trim($row['cedula']);

                // Verificar duplicados por cédula o correo
                if (\App\Models\Persona::where('cedula', $cedula)->exists()) {
                    $duplicados[$fila] = 'Cédula ya registrada: ' . $cedula;
                    continue;
                }
                if (\App\Models\Persona::where('correo', $correo)->exists()) {
                    $duplicados[$fila] = 'Correo ya registrado: ' . $correo;
                    continue;
                }

                // Crear persona
                $persona = \App\Models\Persona::create([
                    'cedula'     => $cedula,
                    'nombres'    => trim($row['nombres']),
                    'apellidos'  => trim($row['apellidos']),
                    'celular'    => trim($row['celular']),
                    'correo'     => $correo,
                    'carrera_id' => $carrera->id_carrera,
                    'cargo_id'   => $cargo->id_cargo,
                ]);

                // Crear usuario automáticamente si no existe
                if (!User::where('email', $correo)->exists()) {
                    User::create([
                        'name' => $persona->nombres . ' ' . $persona->apellidos,
                        'email' => $correo,
                        'password' => Hash::make($cedula), // contraseña = cédula
                    ]);
                }

                $importedCount++;
            }

            DB::commit();

            $mensaje = "Se importaron {$importedCount} registros correctamente.";
            if (count($duplicados) > 0) {
                $mensaje .= " " . count($duplicados) . " registros no se importaron porque ya existen (por cédula o correo).";
            }
            if (count($errores) > 0) {
                $mensaje .= " " . count($errores) . " filas no se importaron por errores de datos.";
            }

            return redirect()->route('personas.index')->with([
                'success'       => $mensaje,
                'import_errors' => $errores,
                'duplicados'    => $duplicados,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Error en la importación: ' . $e->getMessage());
        }
    }
}