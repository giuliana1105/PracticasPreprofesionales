<?php

namespace App\Http\Controllers;

use App\Models\TipoResolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TipoResolucionController extends Controller
{
    public function __construct()
    {
     
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador','coordinadora',  'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'docente', 'estudiante', 'decana'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }
    }
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $tipos = TipoResolucion::all();
    //     return view('tipo_resoluciones.index', compact('tipos'));
    // }


    public function index()
{  $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

    $query = TipoResolucion::query();
    
    // Aplicar filtros
    if (request('filter') == 'recientes') {
        $query->orderBy('created_at', 'desc');
    }
    
    // Aplicar búsqueda
    if (request('search')) {
        $query->where('nombre_tipo_res', 'like', '%'.request('search').'%');
    }
    
    $tipos = $query->paginate(10); // Paginación con 10 elementos por página
    $totalTipos = TipoResolucion::count();
    
    return view('tipo_resoluciones.index', compact('tipos', 'totalTipos'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        return view('tipo_resoluciones.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $request->validate([
            'nombre_tipo_res' => 'required|string|max:255',
        ]);

        TipoResolucion::create($request->all());

        return redirect()->route('tipo_resoluciones.index')
                         ->with('success', 'Tipo de resolución creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {  $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $tipo = TipoResolucion::findOrFail($id);
        return view('tipo_resoluciones.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {  $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        $request->validate([
            'nombre_tipo_res' => 'required|string|max:255',
        ]);

        $tipo = TipoResolucion::findOrFail($id);
        $tipo->update($request->all());

        return redirect()->route('tipo_resoluciones.index')
                         ->with('success', 'Tipo de resolución actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {  $user = Auth::user();
        $persona = $user instanceof \App\Models\User ? $user->persona : $user;
        $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
        if (in_array($cargo, ['coordinador', 'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'docente', 'estudiante'])) {
            abort(403, 'El cargo ' . ucfirst($cargo) . ' no tiene permisos para acceder a esta funcionalidad del sistema.');
        }

        try {
            $tipo = \App\Models\TipoResolucion::findOrFail($id);
            $tipo->delete();
            return redirect()->route('tipo_resoluciones.index')
                ->with('success', 'Tipo de resolución eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Error de restricción de clave foránea
            return redirect()->route('tipo_resoluciones.index')
                ->with('error', 'No se puede eliminar este tipo de resolución porque está referenciado en otra tabla.');
        }
    }
}