@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e9ecef; /* Fondo gris claro general */
        color: #212529; /* Texto oscuro general */
        margin: 0;
        padding-bottom: 20px;
        font-family: sans-serif; /* Fuente predeterminada */
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header-container {
        background-color: #d32f2f; /* Fondo rojo para el encabezado */
        color: #fff;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        margin-bottom: 20px; /* Espacio debajo del encabezado */
        border-radius: 5px; /* Bordes redondeados */
    }

    .header-logo {
        /* Ruta o URL de tu logo de la UTN blanco horizontal */
        
        background-repeat: no-repeat;
        background-size: contain;
        height: 40px; /* Ajusta según la altura de tu logo */
        width: auto; /* Ajusta según el ancho de tu logo */
        margin-right: 15px;
    }

    .header-text-container {
        display: flex;
        flex-direction: column;
    }

    .utn-text {
        font-size: 1.2em;
        font-weight: bold;
    }

    .ibarra-text {
        font-size: 0.9em;
    }

    .page-title {
        background-color: #343a40; /* Fondo gris oscuro para el título */
        color: #fff;
        padding: 20px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 30px;
        font-size: 1.5em;
        font-weight: bold;
    }

    .module-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        padding: 20px;
        background-color: #f8f9fa; /* Fondo gris claro para los módulos */
        border-radius: 5px;
    }

    .module-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 25px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        text-decoration: none;
        color: #495057;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }

    .module-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .module-icon {
        font-size: 2.5em;
        margin-bottom: 15px;
        color: #d32f2f; /* Rojo UTN */
    }

    .module-title {
        font-weight: bold;
        text-align: center;
        font-size: 1.1em;
    }

    /* Ajustes responsivos */
    @media (max-width: 768px) {
        .module-container {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            padding: 10px;
            gap: 10px;
        }
        .module-card {
            padding: 15px;
        }
        .module-icon {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .module-title {
            font-size: 1em;
        }
        .header-logo {
            height: 30px;
            margin-right: 10px;
        }
        .utn-text {
            font-size: 1em;
        }
        .ibarra-text {
            font-size: 0.8em;
        }
        .page-title {
            font-size: 1.3em;
            padding: 15px;
            margin-bottom: 20px;
        }
    }
</style>


@php
    $user = auth()->user();
    $persona = $user ? ($user->persona ?? \App\Models\Persona::where('email', $user->email)->with('cargo')->first()) : null;
    $cargo = strtolower(trim($persona->cargo->nombre_cargo ?? ''));
    $sinPermisoPersonas = in_array($cargo, ['coordinador', 'decano']);
@endphp

<div class="container">
    <div class="header-container">
        <div class="header-logo">
            </div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">PORTAFOLIOS</h1>

    <div id="alertaPermisoHome"></div>

    <div class="module-container">
        @if($cargo === 'estudiante' || $cargo === 'docente')
            <a href="{{ route('titulaciones.index') }}" class="module-card">
                <i class="fas fa-certificate module-icon"></i>
                <div class="module-title">Titulaciones</div>
            </a>
        @else
            <a href="{{ route('cargos.index') }}" class="module-card">
                <i class="fas fa-briefcase module-icon"></i>
                <div class="module-title">Cargos</div>
            </a>
            @if(!$sinPermisoPersonas)
                <a href="{{ route('personas.index') }}" class="module-card">
                    <i class="fas fa-user module-icon"></i>
                    <div class="module-title">Personas</div>
                </a>
            @endif
            <a href="{{ route('carreras.index') }}" class="module-card">
                <i class="fas fa-graduation-cap module-icon"></i>
                <div class="module-title">Carreras</div>
            </a>
            <a href="{{ route('periodos.index') }}" class="module-card">
                <i class="fas fa-calendar-alt module-icon"></i>
                <div class="module-title">Periodos</div>
            </a>
            <a href="{{ route('estado-titulaciones.index') }}" class="module-card">
                <i class="fas fa-flag-checkered module-icon"></i>
                <div class="module-title">Estados de Titulación</div>
            </a>
            <a href="{{ route('resoluciones.index') }}" class="module-card">
                <i class="fas fa-file-alt module-icon"></i>
                <div class="module-title">Resoluciones</div>
            </a>
            <a href="{{ route('titulaciones.index') }}" class="module-card">
                <i class="fas fa-certificate module-icon"></i>
                <div class="module-title">Titulaciones</div>
            </a>
            <a href="{{ route('tipo_resoluciones.index') }}" class="module-card">
                <i class="fas fa-tags module-icon"></i>
                <div class="module-title">Tipos de Resoluciones</div>
            </a>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@if($sinPermisoPersonas)
<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btnPersonas');
    if(btn){
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var alerta = document.getElementById('alertaPermisoHome');
            alerta.innerHTML = '<div class="alert alert-danger mt-2 text-center"><strong>403</strong><br>El cargo {{ ucfirst($cargo) }} no tiene permisos para acceder a esta funcionalidad del sistema.</div>';
        });
    }
});
</script>
@endif
@endpush