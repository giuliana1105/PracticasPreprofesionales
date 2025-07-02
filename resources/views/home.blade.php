@extends('layouts.app')

@section('content')
<style>
    .alineado-home {
        max-width: 1200px;
        margin: 24px auto 0 auto;
    }
    .alineado-home .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 24px 35px 18px 35px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
    }
    .alineado-home .header-logo {
        background-repeat: no-repeat;
        background-size: contain;
        height: 40px;
        width: auto;
        margin-right: 15px;
    }
    .alineado-home .header-text-container {
        display: flex;
        flex-direction: column;
    }
    .alineado-home .utn-text {
        font-size: 1.5em;
        font-weight: bold;
    }
    .alineado-home .ibarra-text {
        font-size: 1.1em;
    }
    .alineado-home .page-title {
        background-color: #343a40;
        color: #fff;
        padding: 28px 0;
        text-align: center;
        border-radius: 8px;
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 0;
    }
    .alineado-home .module-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 5px;
        margin-top: 40px;
    }
    .alineado-home .module-card {
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
    .alineado-home .module-card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .alineado-home .module-icon {
        font-size: 2.5em;
        margin-bottom: 15px;
        color: #d32f2f;
    }
    .alineado-home .module-title {
        font-weight: bold;
        text-align: center;
        font-size: 1.1em;
    }
    @media (max-width: 1280px) {
        .alineado-home { max-width: 98vw; }
    }
    @media (max-width: 768px) {
        .alineado-home .header-container, .alineado-home .page-title {
            padding: 12px 8px;
            font-size: 1em;
        }
        .alineado-home .page-title { font-size: 1.1em; }
        .alineado-home .module-container {
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            padding: 10px;
            gap: 10px;
        }
        .alineado-home .module-card {
            padding: 15px;
        }
        .alineado-home .module-icon {
            font-size: 2em;
            margin-bottom: 10px;
        }
        .alineado-home .module-title {
            font-size: 1em;
        }
        .alineado-home .header-logo {
            height: 30px;
            margin-right: 10px;
        }
        .alineado-home .utn-text {
            font-size: 1em;
        }
        .alineado-home .ibarra-text {
            font-size: 0.8em;
        }
    }
</style>


@php
    $user = auth()->user();
    $persona = $user ? \App\Models\Persona::where('email', $user->email)->first() : null;
    $cargo = strtolower(trim($persona->cargo ?? ''));
    $sinPermisoPersonas = !in_array($cargo, [
        'decano', 'subdecano', 'subdecana', 'abogado', 'abogada', 'secretario', 'secretario general'
    ]);
@endphp
<div class="alineado-home">
    <div class="header-container">
        <div class="header-logo"></div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>
    <div class="page-title">
        PORTAFOLIOS
    </div>
    <div id="alertaPermisoHome"></div>
    <div class="module-container">
        @if(in_array($cargo, ['decano', 'subdecano', 'subdecana','coordinador','coordinadora',  'abogado', 'abogada', 'secretario','estudiante','docente', 'secretario general']))
            {{-- Muestra el botón de Titulaciones con el mismo diseño de tarjeta --}}
            <a href="{{ route('titulaciones.index') }}" class="module-card">
                <i class="fas fa-certificate module-icon"></i>
                <div class="module-title">Titulaciones</div>
            </a>
        @else
            <a href="{{ route('personas.index') }}" class="module-card" id="btnPersonas">
                <i class="fas fa-users module-icon"></i>
                <div class="module-title">Personas</div>
            </a>
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
@endsection