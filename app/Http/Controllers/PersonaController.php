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
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Validator;

class PersonaController extends Controller
{
    public function __construct()
    {
         
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
    }

    // Mostrar todas las personas
    public function index(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $query = Persona::with(['carrera', 'cargo']);

        // Búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('cedula', 'ILIKE', "%$buscar%")
                  ->orWhere('nombres', 'ILIKE', "%$buscar%")
                  ->orWhere('apellidos', 'ILIKE', "%$buscar%");
            });
        }

        // Filtro de recientes
        if ($request->filtro === 'recientes') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->orderBy('id', 'desc');
        }

        $personas = $query->get();

        return view('personas.index', compact('personas'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

    
        $carreras = Carrera::all();
        $cargos = Cargo::all();
        return view('personas.create', compact('carreras', 'cargos'));
    }

    // Almacenar nueva persona
    public function store(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $validator = Validator::make($request->all(), [
            'cedula' => 'required|string|max:20|unique:personas,cedula',
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'celular' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:personas,email',
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'cargo_id' => 'required|exists:cargos,id_cargo',
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombres.required' => 'Los nombres son obligatorios',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'celular.required' => 'El celular es obligatorio',
            'email.required' => 'El email electrónico es obligatorio',
            'email.email' => 'Ingrese un email electrónico válido',
            'email.unique' => 'Este email electrónico ya está registrado',
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
            $persona->load('cargo');
            $cargoNombre = $persona->cargo ? strtolower(trim($persona->cargo->nombre_cargo)) : null;

            // Crea el usuario automáticamente si no existe y el email no es nulo/ vacío
            if (!empty($persona->email) && !User::where('email', $persona->email)->exists()) {
                User::create([
                    'name' => $persona->nombres . ' ' . $persona->apellidos,
                    'email' => $persona->email,
                    'password' => Hash::make($persona->cedula), // SIEMPRE hasheada
                    'role' => $cargoNombre,
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
    { $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
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
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $persona = Persona::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'cedula' => 'required|string|max:20|unique:personas,cedula,'.$id,
            'nombres' => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'celular' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:personas,email,'.$id,
            'carrera_id' => 'required|exists:carreras,id_carrera',
            'cargo_id' => 'required|exists:cargos,id_cargo',
        ], [
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'nombres.required' => 'Los nombres son obligatorios',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'celular.required' => 'El celular es obligatorio',
            'email.required' => 'El email electrónico es obligatorio',
            'email.email' => 'Ingrese un email electrónico válido',
            'email.unique' => 'Este email electrónico ya está registrado',
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
            $oldEmail = $persona->email;
            $persona->update($request->all());

            // Obtener el nombre del cargo para el rol
            $cargoNombre = $persona->cargo ? strtolower(trim($persona->cargo->nombre_cargo)) : null;

            // Si el email cambió y no existe usuario con ese email, crea el usuario
            if ($persona->email !== $oldEmail && !empty($persona->email) && !User::where('email', $persona->email)->exists()) {
                User::create([
                    'name' => $persona->nombres . ' ' . $persona->apellidos,
                    'email' => $persona->email,
                    'password' => Hash::make($persona->cedula),
                    'role' => $cargoNombre,
                ]);
            }

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
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
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
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
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

        // Normalizar encabezados
        $header = $csv->getHeader();
        $normalize = function($str) {
            $str = mb_strtolower($str, 'UTF-8');
            $str = str_replace(
                ['á','é','í','ó','ú','ñ','Á','É','Í','Ó','Ú','Ñ'],
                ['a','e','i','o','u','n','a','e','i','o','u','n'],
                $str
            );
            $str = preg_replace('/[^a-z0-9_]/', '', $str); // quitar espacios y símbolos
            return $str;
        };

        // Mapeo de encabezados normalizados a los nombres internos
        $map = [
            'cedula'        => ['cedula', 'cédula'],
            'nombres'       => ['nombres'],
            'apellidos'     => ['apellidos'],
            'celular'       => ['celular'],
            'email'         => ['email', 'correo', 'correo_electronico'],
            'sigla_carrera' => ['siglacarrera', 'sigla_carrera', 'sigla carrera', 'siglas', 'siglas_carrera', 'siglas carrera'],
            'cargo'         => ['cargo'],
        ];

        // Crear un array de encabezados normalizados => nombre interno
        $headerMap = [];
        foreach ($header as $h) {
            $norm = $normalize($h);
            foreach ($map as $key => $variants) {
                foreach ($variants as $variant) {
                    if ($norm === $normalize($variant)) {
                        $headerMap[$norm] = $key;
                    }
                }
            }
        }

        DB::beginTransaction();
        $importedCount = 0;
        $duplicados = [];
        $errores = [];

        try {
            foreach ($csv->getRecords() as $index => $row) {
                $fila = $index + 2;

                // Normalizar las claves del row
                $normalizedRow = [];
                foreach ($row as $k => $v) {
                    $norm = $normalize($k);
                    if (isset($headerMap[$norm])) {
                        $normalizedRow[$headerMap[$norm]] = $v;
                    }
                }

                // Validar campos requeridos mínimos
                if (empty($normalizedRow['cedula'])) {
                    $errores[$fila] = 'Cédula vacía';
                    continue;
                }
                if (empty($normalizedRow['nombres'])) {
                    $errores[$fila] = 'Nombres vacíos';
                    continue;
                }
                if (empty($normalizedRow['apellidos'])) {
                    $errores[$fila] = 'Apellidos vacíos';
                    continue;
                }
                if (empty($normalizedRow['celular'])) {
                    $errores[$fila] = 'Celular vacío';
                    continue;
                }
                if (empty($normalizedRow['email'])) {
                    $errores[$fila] = 'email vacío';
                    continue;
                }
                if (empty($normalizedRow['sigla_carrera'])) {
                    $errores[$fila] = 'Sigla de carrera vacía';
                    continue;
                }
                if (empty($normalizedRow['cargo'])) {
                    $errores[$fila] = 'Cargo vacía';
                    continue;
                }

                // Buscar carrera por SIGLAS (nuevo)
                $carrera = \App\Models\Carrera::whereRaw('LOWER(siglas_carrera) = ?', [strtolower(trim($normalizedRow['sigla_carrera']))])->first();
                if (! $carrera) {
                    $errores[$fila] = 'Carrera no encontrada por sigla: ' . $normalizedRow['sigla_carrera'];
                    continue;
                }

                // Buscar cargo por nombre (igual que antes)
                $cargo = \App\Models\Cargo::whereRaw('LOWER(nombre_cargo) = ?', [strtolower(trim($normalizedRow['cargo']))])->first();
                if (! $cargo) {
                    $errores[$fila] = 'Cargo no encontrado: ' . $normalizedRow['cargo'];
                    continue;
                }

                $email = trim(str_replace(['"', "'", ' '], '', $normalizedRow['email']));
                $cedula = trim($normalizedRow['cedula']);
                $celular = trim($normalizedRow['celular']);

                // Si el número tiene 9 dígitos y empieza por 9, agrega el 0 al inicio
                if (preg_match('/^9\d{8}$/', $celular)) {
                    $celular = '0' . $celular;
                }

                // Verificar duplicados por cédula o email
                if (\App\Models\Persona::where('cedula', $cedula)->exists()) {
                    $duplicados[$fila] = 'Cédula ya registrada: ' . $cedula;
                    continue;
                }
                if (\App\Models\Persona::where('email', $email)->exists()) {
                    $duplicados[$fila] = 'email ya registrado: ' . $email;
                    continue;
                }

                // Crear persona
                $persona = \App\Models\Persona::create([
                    'cedula'     => $cedula,
                    'nombres'    => trim($normalizedRow['nombres']),
                    'apellidos'  => trim($normalizedRow['apellidos']),
                    'celular'    => $celular,
                    'email'      => $email,
                    'carrera_id' => $carrera->id_carrera,
                    'cargo_id'   => $cargo->id_cargo,
                ]);

                // Crear usuario automáticamente si no existe
                if (!User::where('email', $email)->exists()) {
                    User::create([
                        'name' => $persona->nombres . ' ' . $persona->apellidos,
                        'email' => $email,
                        'password' => Hash::make($cedula), // SIEMPRE hasheada
                        'role' => strtolower(trim($cargo->nombre_cargo)),
                    ]);
                }

                $importedCount++;
            }

            DB::commit();

            $mensaje = "Se importaron {$importedCount} registros correctamente.";
            if (count($duplicados) > 0) {
                $mensaje .= " " . count($duplicados) . " registros no se importaron porque ya existen (por cédula o email).";
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

    public function resetPassword($id)
    {
        $user = auth()->user();
        $personaAuth = $user ? ($user->persona ?? \App\Models\Persona::where('email', $user->email)->with('cargo')->first()) : null;
        $esAdmin = $personaAuth && strtolower(trim($personaAuth->cargo->nombre_cargo ?? '')) === 'administrador';

        if (!$esAdmin) {
            abort(403, 'No autorizado');
        }

        $persona = Persona::findOrFail($id);
        $usuario = User::where('email', $persona->email)->first();

        if ($usuario) {
            $usuario->password = Hash::make($persona->cedula);
            $usuario->must_change_password = true;
            $usuario->save();
            return redirect()->route('personas.index')->with('success', 'Contraseña reseteada correctamente. El usuario deberá cambiarla al ingresar.');
        } else {
            return redirect()->route('personas.index')->with('error', 'No existe usuario asociado a esta persona.');
        }
    }
}