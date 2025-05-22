<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use App\Models\Resolucion;
use App\Models\ResolucionSeleccionada;
use App\Models\ResolucionTema;
use Illuminate\Http\Request;

class TemaController extends Controller
{
    // Mostrar todos los temas
    public function index()
    {
        // Obtener todos los temas de la base de datos
        $temas = Tema::all();
        
        // Retornar la vista con los temas
        return view('temas.index', compact('temas'));
    }

    // Mostrar el formulario para crear un nuevo tema
    public function create()
    {
        // Si necesitas pasar temas a la vista, obtén los datos
        $temas = Tema::all();

        // Pasar los datos a la vista
        return view('temas.create', compact('temas'));
    }

    // Guardar un nuevo tema
    // public function store(Request $request)
    // {
    //     // Validar los datos del formulario
    //     $request->validate([
    //         'nombre_tema' => 'required|string|max:255',
    //     ]);

    //     // Crear un nuevo tema
    //     $tema = Tema::create([
    //         'nombre_tema' => $request->input('nombre_tema'),
    //     ]);

    //     // Obtener las resoluciones seleccionadas
    //     $resolucionesSeleccionadas = ResolucionSeleccionada::all();

    //     // Verificar si hay resoluciones seleccionadas
    //     if ($resolucionesSeleccionadas->isEmpty()) {
    //         return redirect()->route('temas.create')->with('error', 'No hay resoluciones seleccionadas para asociar con este tema.');
    //     }

    //     // Asociar el tema con las resoluciones seleccionadas
    //     foreach ($resolucionesSeleccionadas as $resolucionSeleccionada) {
    //         ResolucionTema::create([
    //             'resolucion_id' => $resolucionSeleccionada->resolucion_id,
    //             'tema_id' => $tema->id_tema,
    //         ]);
    //     }

    //     // Redirigir al CRUD de temas con un mensaje de éxito
    //     return redirect()->route('temas.create')->with('success', 'Tema creado y asociado a las resoluciones seleccionadas exitosamente.');
    //}


// public function store(Request $request)
// {
//     // Validar los datos del formulario
//     $request->validate([
//         'nombre_tema' => 'required|string|max:255',
//     ]);

//     // Obtener las resoluciones seleccionadas
//     $resolucionesSeleccionadas = ResolucionSeleccionada::all();

//     // Verificar si hay resoluciones seleccionadas
//     if ($resolucionesSeleccionadas->isEmpty()) {
//         return redirect()->route('temas.create')->with('error', 'No hay resoluciones seleccionadas para asociar con este tema.');
//     }

//     // Permitir varios temas, uno por línea
//     $temas = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $request->input('nombre_tema'))));
//     $temasCreados = 0;

//     foreach ($temas as $temaNombre) {
//         if ($temaNombre !== '') {
//             // Crear un nuevo tema
//             $tema = Tema::create([
//                 'nombre_tema' => $temaNombre,
//             ]);
//             $temasCreados++;

//             // Asociar el tema con las resoluciones seleccionadas
//             foreach ($resolucionesSeleccionadas as $resolucionSeleccionada) {
//                 ResolucionTema::create([
//                     'resolucion_id' => $resolucionSeleccionada->resolucion_id,
//                     'tema_id' => $tema->id_tema,
//                 ]);
//             }
//         }
//     }

//     // Redirigir al CRUD de temas con un mensaje de éxito
//     return redirect()->route('temas.create')->with('success', $temasCreados . ' tema(s) creado(s) y asociado(s) a las resoluciones seleccionadas exitosamente.');
// }



public function store(Request $request)
{
    // Validar los datos del formulario
    $request->validate([
        'nombre_tema' => 'required|string|max:255',
    ]);

    // Obtener las resoluciones seleccionadas
    $resolucionesSeleccionadas = ResolucionSeleccionada::all();

    // Verificar si hay resoluciones seleccionadas
    if ($resolucionesSeleccionadas->isEmpty()) {
        return redirect()->route('temas.create')->with('error', 'No hay resoluciones seleccionadas para asociar con este tema.');
    }

    // Permitir varios temas, uno por línea, y eliminar viñetas vacías
    $temas = array_filter(array_map(function($line) {
        // Quita la viñeta y espacios al inicio
        $tema = trim(preg_replace('/^•\s*/u', '', $line));
        return $tema !== '' ? $tema : null;
    }, preg_split('/\r\n|\r|\n/', $request->input('nombre_tema'))));

    $temasCreados = 0;

    foreach ($temas as $temaNombre) {
        // Crear un nuevo tema
        $tema = Tema::create([
            'nombre_tema' => $temaNombre,
        ]);
        $temasCreados++;

        // Asociar el tema con las resoluciones seleccionadas
        foreach ($resolucionesSeleccionadas as $resolucionSeleccionada) {
            ResolucionTema::create([
                'resolucion_id' => $resolucionSeleccionada->resolucion_id,
                'tema_id' => $tema->id_tema,
            ]);
        }
    }

    // Redirigir al CRUD de temas con un mensaje de éxito
    return redirect()->route('temas.create')->with('success', $temasCreados . ' tema(s) creado(s) y asociado(s) a las resoluciones seleccionadas exitosamente.');
}

    // Mostrar el formulario de edición para un tema específico
    public function edit($id_tema)
    {
        // Obtener el tema por ID
        $tema = Tema::findOrFail($id_tema);

        return view('temas.edit', compact('tema'));
    }

    // Actualizar un tema existente
    public function update(Request $request, $id_tema)
    {
        // Validar los datos
        $request->validate([
            'nombre_tema' => 'required|string|max:255',
        ]);

        // Buscar el tema a actualizar
        $tema = Tema::findOrFail($id_tema);

        // Actualizar el nombre del tema
        $tema->update([
            'nombre_tema' => $request->nombre_tema,
        ]);

        return redirect()->route('temas.create')->with('success', 'Tema actualizado exitosamente.');
    }

    // Eliminar un tema
    public function destroy($id_tema)
    {
        // Buscar el tema a eliminar
        $tema = Tema::findOrFail($id_tema);
         // Eliminar todas las titulaciones relacionadas con este tema
         if ($tema->titulaciones) {
        foreach ($tema->titulaciones as $titulacion) {
            $titulacion->delete();
        }
        }
        // Eliminar relaciones con resoluciones (si tienes tabla pivote)
    if ($tema->resoluciones) {
        $tema->resoluciones()->detach();
    }

        // Eliminar el tema
        $tema->delete();

        return redirect()->route('temas.create')->with('success', 'Tema eliminado exitosamente.');
    }

    // Formulario para seleccionar resoluciones antes de crear temas
    public function selectResoluciones()
    {
        $resoluciones = Resolucion::all();

        return view('temas.selectResoluciones', compact('resoluciones'));
    }
}
