<?php

namespace App\Http\Controllers;

use App\Models\Resolucion;
use App\Models\Tema;
use App\Models\ResTema;
use App\Models\TipoResolucion;
use App\Models\ResolucionSeleccionada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ResolucionController extends Controller
{
    public function __construct()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        // Cambia para usar el campo string 'cargo' o el rol seleccionado
        $cargo = session('selected_role') ? strtolower(trim(session('selected_role'))) : strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinadora','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'decana'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));

        // Filtro para secretaria
        $esSecretaria = $cargo === 'secretaria' || $cargo === 'secretario'|| $cargo === 'secretario/a';

        $query = Resolucion::with(['tipoResolucion', 'carrera']);

        // FILTRO: Si es secretaria, solo muestra resoluciones de sus carreras asignadas
        if ($esSecretaria) {
            // Asegúrate de tener la relación carreras() en tu modelo Persona
            $carrerasAsignadas = $persona->carreras()->pluck('id_carrera')->toArray();
            if (!empty($carrerasAsignadas)) {
                $query->whereIn('carrera_id', $carrerasAsignadas);
            } else {
                // Si no tiene carreras asignadas, no muestra ninguna resolución
                $query->whereRaw('1=0');
            }
        }

        // Filtro de búsqueda
        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function($q) use ($buscar) {
                $q->where('numero_res', 'ILIKE', "%$buscar%")
                  ->orWhere('fecha_res', 'ILIKE', "%$buscar%")
                  ->orWhereHas('tipoResolucion', function($q2) use ($buscar) {
                      $q2->where('nombre_tipo_res', 'ILIKE', "%$buscar%");
                  });
            });
        }

        // Filtro por carrera
        if ($request->filled('carrera_filtro')) {
            $query->where('carrera_id', $request->carrera_filtro);
        }

        // Filtro de recientes
        if ($request->filtro === 'recientes') {
            $query->orderBy('fecha_res', 'desc');
        } else {
            $query->orderBy('id_Reso', 'desc');
        }

        $resoluciones = $query->paginate(10);

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
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $resolucionesIds = $request->input('resoluciones', []);
        $resolucionesSeleccionadas = Resolucion::whereIn('id_Reso', $resolucionesIds)->get();
        $temas = Tema::all();

        return view('temas.create', compact('resolucionesSeleccionadas', 'temas'));
    }

    public function procesarSeleccion(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

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
        $user = auth()->user();
        $cargo = strtolower(trim($user->cargo ?? ''));

        // Si es secretaria/o, solo muestra sus carreras asignadas
        if (in_array($cargo, ['secretario', 'secretaria','secretario/a'])) {
            $carreras = $user->persona ? $user->persona->carreras()->get() : collect();
        } else {
            $carreras = \App\Models\Carrera::all();
        }

        $tipos = \App\Models\TipoResolucion::all();

        // Mostrar la vista para crear la resolución
        return view('resoluciones.create', compact('carreras', 'tipos'));
    }

    public function storeTemas(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

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
                
                ResTema::firstOrCreate([
                    'resolucion_id' => $resolucionId,
                    'tema_id' => $tema->id_tema
                ]);
            }
        }

        return redirect()->route('resoluciones.index')->with('success', 'Temas asignados exitosamente.');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        // Validar los datos del formulario
        $request->validate([
            'numero_res' => 'required|string|max:50',
            'fecha_res' => 'required|date_format:Y-m-d',
            'tipo_res' => 'required|exists:tipo_resoluciones,id_tipo_res',
            'archivo_pdf' => 'required|file|mimes:pdf|max:2048',
            'carrera_id' => 'required|exists:carreras,id_carrera',
        ]);

        // Validación manual de duplicados por número y carrera
        $existe = \App\Models\Resolucion::where('numero_res', $request->numero_res)
            ->where('carrera_id', $request->carrera_id)
            ->exists();
        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ya existe una resolución con ese número para la carrera seleccionada.');
        }

        // Almacenar el archivo PDF
        $archivoPath = $request->file('archivo_pdf')->store('resoluciones', 'public');

        // Crear la nueva resolución
        Resolucion::create([
            'numero_res' => $request->numero_res,
            'fecha_res' => $request->fecha_res,
            'tipo_res' => $request->tipo_res,
            'archivo_pdf' => $archivoPath, // Guardar la ruta relativa, ej: resoluciones/archivo.pdf
            'carrera_id' => $request->carrera_id,
        ]);

        // Redirigir a la lista de resoluciones with a success message
        return redirect()->route('resoluciones.index')
                         ->with('success', 'Resolución creada exitosamente.');
    }

    public function seleccionarResoluciones(Request $request)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $resolucionesIds = $request->input('resoluciones', []);

        // Filtrar solo valores numéricos
        $resolucionesIds = array_filter($resolucionesIds, function($id) {
            return is_numeric($id);
        });

        if (empty($resolucionesIds)) {
            return redirect()->route('resoluciones.index')->with('error', 'Debe seleccionar al menos una resolución.');
        }

        // Eliminar solo las resoluciones seleccionadas por este usuario
        ResolucionSeleccionada::where('user_id', $user->id)->delete();

        foreach ($resolucionesIds as $id) {
            ResolucionSeleccionada::create([
                'resolucion_id' => $id,
                'user_id' => $user->id
            ]);
        }

        return redirect()->route('titulaciones.index')->with('success', 'Resoluciones seleccionadas correctamente. Ahora puede ingresar los temas.');
    }

    public function cambiarResoluciones()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        // Eliminar solo las resoluciones seleccionadas por este usuario
        $user = Auth::user();
        \App\Models\ResolucionSeleccionada::where('user_id', $user->id)->delete();

        // Redirigir a la pantalla de selección de resoluciones
        return redirect()->route('resoluciones.index')->with('success', 'Resoluciones limpiadas solo para usted. Seleccione nuevas resoluciones.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $resolucion = Resolucion::findOrFail($id);

        // Validar si la resolución está referenciada en otras tablas
        $estaReferenciada = 
            $resolucion->temas()->exists() || 
            $resolucion->titulaciones()->exists() ||
            $resolucion->resolucionesSeleccionadas()->exists();

        if ($estaReferenciada) {
            return redirect()->route('resoluciones.index')
                ->with('error', 'No se puede eliminar la resolución porque está referenciada en otra tabla.');
        }

        // Si tienes archivos asociados, puedes eliminarlos del storage si lo deseas:
        // Storage::delete('public/' . $resolucion->archivo_pdf);

        $resolucion->delete();

        return redirect()->route('resoluciones.index')->with('success', 'Resolución eliminada exitosamente.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        // Puedes dejarlo vacío o redirigir a index
        return redirect()->route('resoluciones.index');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));

        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $resolucion = \App\Models\Resolucion::findOrFail($id);
        $tipos = \App\Models\TipoResolucion::all();

        // Solo muestra las carreras asignadas si es secretaria/o
        if (in_array($cargo, ['secretario', 'secretaria','secretario/a'])) {
            $carreras = $persona && method_exists($persona, 'carreras')
                ? $persona->carreras()->get()
                : collect();
        } else {
            $carreras = \App\Models\Carrera::all();
        }

        // Agrega la URL del archivo igual que en index
        $resolucion->archivo_url = $resolucion->archivo_pdf
            ? asset('storage/' . $resolucion->archivo_pdf)
            : null;

        return view('resoluciones.edit', compact('resolucion', 'tipos', 'carreras'));
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinador/a', 'decano','decano/a', 'subdecano', 'subdecana','subdecano/a', 'abogado', 'abogada','abogado/a', 'docente', 'estudiante', 'secretario_general'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $resolucion = \App\Models\Resolucion::findOrFail($id);

        $request->validate([
            'numero_res' => 'required|string|max:255',
            'fecha_res' => 'required|date',
            'tipo_res' => 'required|exists:tipo_resoluciones,id_tipo_res',
            'archivo_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'carrera_id' => 'required|exists:carreras,id_carrera',
        ]);

        $data = [
            'numero_res' => $request->numero_res,
            'fecha_res' => $request->fecha_res,
            'tipo_res' => $request->tipo_res,
            'carrera_id' => $request->carrera_id,
        ];

        // Si se sube un nuevo archivo PDF, reemplázalo
        if ($request->hasFile('archivo_pdf')) {
            // Borra el anterior si existe
            if ($resolucion->archivo_pdf && Storage::disk('public')->exists($resolucion->archivo_pdf)) {
                Storage::disk('public')->delete($resolucion->archivo_pdf);
            }
            $data['archivo_pdf'] = $request->file('archivo_pdf')->store('resoluciones', 'public');
        }

        $resolucion->update($data);

        return redirect()->route('resoluciones.index')->with('success', 'Resolución actualizada exitosamente.');
    }
}
