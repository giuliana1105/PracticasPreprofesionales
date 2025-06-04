<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResolucionController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\TipoResolucionController;
use App\Http\Controllers\EstadoTitulacionController;
use App\Http\Controllers\TitulacionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResTemaController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Route::resource('tipo_resoluciones', TipoResolucionController::class);
// Route::resource('resoluciones', ResolucionController::class);

// Route::get('/resoluciones', [ResolucionController::class, 'index'])->name('resoluciones.index');
// Route::post('/resoluciones/seleccionar', [ResolucionController::class, 'seleccionarResoluciones'])->name('resoluciones.seleccionar');
// Route::get('resoluciones/cambiar', [App\Http\Controllers\ResolucionController::class, 'cambiarResoluciones'])->name('resoluciones.cambiar');

// Route::resource('temas', TemaController::class);

// Route::get('/temas/create', [TemaController::class, 'create'])->name('temas.create');
// Route::post('/temas/store', [TemaController::class, 'store'])->name('temas.store');

// Route::post('resoluciones/temas', [ResolucionController::class, 'storeTemas'])->name('resoluciones.temas.store');
// Route::get('resoluciones/temas', [ResolucionController::class, 'createTemas'])->name('resoluciones.temas.create');

// Route::get('/resoluciones/temas/create', [ResolucionController::class, 'createTemas'])
//      ->name('resoluciones.temas.create');

// Route::post('/resoluciones/procesar-seleccion', [ResolucionController::class, 'procesarSeleccion'])
// ->name('resoluciones.procesar.seleccion');

// Route::post('/resoluciones/store-temas', [ResolucionController::class, 'storeTemas'])
//      ->name('resoluciones.storeTemas');

// Route::post('/resoluciones/storeTemas', [ResolucionController::class, 'storeTemas'])->name('resoluciones.storeTemas');

// Route::get('/resoluciones/temas/create', [ResolucionController::class, 'createTemas'])
//      ->name('resoluciones.temas.create');

// Route::resource('carreras', CarreraController::class);
// Route::resource('cargos', CargoController::class);
// Route::resource('personas', PersonaController::class);

// Route::get('/personas/import', [PersonaController::class, 'showImportForm'])->name('personas.import.form');
// Route::post('/personas/import', [PersonaController::class, 'import'])->name('personas.import');

// Route::resource('personas', PersonaController::class);

// Route::post('personas/import', [PersonaController::class, 'import'])->name('personas.import');
// Route::resource('periodos', PeriodoController::class);
// Route::resource('estado-titulaciones', EstadoTitulacionController::class);
// //Route::resource('titulaciones', TitulacionController::class);
// Route::post('titulaciones/import-csv', [TitulacionController::class, 'importCsv'])->name('titulaciones.importCsv');
// Route::get('/temas', [TemaController::class, 'index'])->name('temas.index');
// Route::get('/titulaciones/create', [TitulacionController::class, 'create'])->name('titulaciones.create');
// Route::post('/titulaciones/store', [TitulacionController::class, 'store'])->name('titulaciones.store');
// Route::post('/temas/store', [TemaController::class, 'store'])->name('temas.store');

// Route::get('/temas', [TemaController::class, 'index'])->name('temas.index');
// Route::post('/resoluciones/seleccionar', [ResolucionController::class, 'seleccionarResoluciones'])->name('resoluciones.seleccionar');
// Route::get('/temas/create', [TemaController::class, 'create'])->name('temas.create');
// Route::post('/temas/store', [TemaController::class, 'store'])->name('temas.store');
// Route::delete('/titulaciones/{titulacion}', [TitulacionController::class, 'destroy'])->name('titulaciones.destroy');
// Route::put('/titulaciones/{titulacion}', [TitulacionController::class, 'update'])->name('titulaciones.update');
// Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::resource('res_temas', ResTemaController::class)->only(['index', 'store', 'destroy']);

// // Ruta para generar PDF (debe ir antes)
// Route::get('titulaciones/pdf', [TitulacionController::class, 'pdf'])->name('titulaciones.pdf');

// // Rutas CRUD de titulaciones
// Route::get('titulaciones', [TitulacionController::class, 'index'])->name('titulaciones.index');
// Route::get('titulaciones/create', [TitulacionController::class, 'create'])->name('titulaciones.create');
// Route::post('titulaciones/store', [TitulacionController::class, 'store'])->name('titulaciones.store');
// Route::get('titulaciones/{titulacion}', [TitulacionController::class, 'show'])->name('titulaciones.show');
// Route::get('titulaciones/{titulacion}/edit', [TitulacionController::class, 'edit'])->name('titulaciones.edit');
// Route::put('titulaciones/{titulacion}', [TitulacionController::class, 'update'])->name('titulaciones.update');
// Route::delete('titulaciones/{titulacion}', [TitulacionController::class, 'destroy'])->name('titulaciones.destroy');
// Route::post('titulaciones/import-csv', [TitulacionController::class, 'importCsv'])->name('titulaciones.importCsv');
// Route::delete('/resoluciones/{id}', [ResolucionController::class, 'destroy'])->name('resoluciones.destroy');

// Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
// Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
// Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::middleware('auth')->group(function () {
    // Todas tus rutas protegidas aquí
    Route::resource('tipo_resoluciones', TipoResolucionController::class);
    Route::resource('resoluciones', ResolucionController::class);

    Route::get('/resoluciones', [ResolucionController::class, 'index'])->name('resoluciones.index');
    Route::post('/resoluciones/seleccionar', [ResolucionController::class, 'seleccionarResoluciones'])->name('resoluciones.seleccionar');
    Route::get('resoluciones/cambiar', [App\Http\Controllers\ResolucionController::class, 'cambiarResoluciones'])->name('resoluciones.cambiar');

    Route::resource('temas', TemaController::class);

    Route::get('/temas/create', [TemaController::class, 'create'])->name('temas.create');
    Route::post('/temas/store', [TemaController::class, 'store'])->name('temas.store');

    Route::post('resoluciones/temas', [ResolucionController::class, 'storeTemas'])->name('resoluciones.temas.store');
    Route::get('resoluciones/temas', [ResolucionController::class, 'createTemas'])->name('resoluciones.temas.create');

    Route::get('/resoluciones/temas/create', [ResolucionController::class, 'createTemas'])
         ->name('resoluciones.temas.create');

    Route::post('/resoluciones/procesar-seleccion', [ResolucionController::class, 'procesarSeleccion'])
    ->name('resoluciones.procesar.seleccion');

    Route::post('/resoluciones/store-temas', [ResolucionController::class, 'storeTemas'])
         ->name('resoluciones.storeTemas');

    Route::post('/resoluciones/storeTemas', [ResolucionController::class, 'storeTemas'])->name('resoluciones.storeTemas');

    Route::get('/resoluciones/temas/create', [ResolucionController::class, 'createTemas'])
         ->name('resoluciones.temas.create');

    Route::resource('carreras', CarreraController::class);
    Route::resource('cargos', CargoController::class);
    Route::resource('personas', PersonaController::class);

    Route::get('/personas/import', [PersonaController::class, 'showImportForm'])->name('personas.import.form');
    Route::post('/personas/import', [PersonaController::class, 'import'])->name('personas.import');

    Route::resource('personas', PersonaController::class);

    Route::post('personas/import', [PersonaController::class, 'import'])->name('personas.import');
    Route::resource('periodos', PeriodoController::class);
    Route::resource('estado-titulaciones', EstadoTitulacionController::class);
    //Route::resource('titulaciones', TitulacionController::class);
    Route::post('titulaciones/import-csv', [TitulacionController::class, 'importCsv'])->name('titulaciones.importCsv');
    Route::get('/temas', [TemaController::class, 'index'])->name('temas.index');
    Route::get('/titulaciones/create', [TitulacionController::class, 'create'])->name('titulaciones.create');
    Route::post('/titulaciones/store', [TitulacionController::class, 'store'])->name('titulaciones.store');
    Route::post('/temas/store', [TemaController::class, 'store'])->name('temas.store');

    Route::get('/temas', [TemaController::class, 'index'])->name('temas.index');
    Route::post('/resoluciones/seleccionar', [ResolucionController::class, 'seleccionarResoluciones'])->name('resoluciones.seleccionar');
    Route::get('/temas/create', [TemaController::class, 'create'])->name('temas.create');
    Route::post('/temas/store', [TemaController::class, 'store'])->name('temas.store');
    Route::delete('/titulaciones/{titulacion}', [TitulacionController::class, 'destroy'])->name('titulaciones.destroy');
    Route::put('/titulaciones/{titulacion}', [TitulacionController::class, 'update'])->name('titulaciones.update');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('res_temas', ResTemaController::class)->only(['index', 'store', 'destroy']);

    // Ruta para generar PDF (debe ir antes)
    Route::get('titulaciones/pdf', [TitulacionController::class, 'pdf'])->name('titulaciones.pdf');

    // Rutas CRUD de titulaciones
    Route::get('titulaciones', [TitulacionController::class, 'index'])->name('titulaciones.index');
    Route::get('titulaciones/create', [TitulacionController::class, 'create'])->name('titulaciones.create');
    Route::post('titulaciones/store', [TitulacionController::class, 'store'])->name('titulaciones.store');
    Route::get('titulaciones/{titulacion}', [TitulacionController::class, 'show'])->name('titulaciones.show');
    Route::get('titulaciones/{titulacion}/edit', [TitulacionController::class, 'edit'])->name('titulaciones.edit');
    Route::put('titulaciones/{titulacion}', [TitulacionController::class, 'update'])->name('titulaciones.update');
    Route::delete('titulaciones/{titulacion}', [TitulacionController::class, 'destroy'])->name('titulaciones.destroy');
    Route::post('titulaciones/import-csv', [TitulacionController::class, 'importCsv'])->name('titulaciones.importCsv');
    Route::delete('/resoluciones/{id}', [ResolucionController::class, 'destroy'])->name('resoluciones.destroy');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
