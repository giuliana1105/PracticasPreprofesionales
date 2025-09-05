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
    $cargo = $persona ? $persona->cargo : '';
    $cargosCompuestos = ['docente-decano/a', 'docente-subdecano/a'];
    $rolActual = session('selected_role');
    $rolAlternativo = null;
    // Solo mostrar el botón si el cargo original es compuesto y hay rol seleccionado
    if (in_array(strtolower($cargo), $cargosCompuestos) && $rolActual) {
        if (strpos(strtolower($cargo), 'decano') !== false) {
            $rolAlternativo = $rolActual === 'docente' ? 'decano/a' : 'docente';
        } elseif (strpos(strtolower($cargo), 'subdecano') !== false) {
            $rolAlternativo = $rolActual === 'docente' ? 'subdecano/a' : 'docente';
        }
    }
    $sinPermisoPersonas = !in_array(strtolower($cargo), [
        'secretario', 'secretaria', 'secretario_general', 'secretario/a', 'secretaria/o', 'secretario general', 'secretaria general'
    ]);
    $rolParaPermisos = $rolActual ? strtolower($rolActual) : strtolower($cargo);
@endphp
<div class="alineado-home">
    <div class="header-container justif">
        <div class="header-logo"></div>
       
        <div class="d-flex align-items-center justify-content-center bg-danger text-white p-3 rounded mb-4 w-100">
    <img src="{{ asset('img/utn_logo.png') }}" alt="Logo UTN" style="height: 100px; display: block; margin: 0 auto;">
</div>
    </div>
    <div class="page-title">
        PORTAFOLIOS
    </div>
    <div id="alertaPermisoHome"></div>
    <div class="module-container">
        @if($rolParaPermisos === 'decano' || $rolParaPermisos === 'decano/a')
            {{-- Decano/a: solo titulaciones --}}
            <a href="{{ route('titulaciones.index') }}" class="module-card">
                <i class="fas fa-certificate module-icon"></i>
                <div class="module-title">Titulaciones</div>
            </a>
        @elseif($rolParaPermisos === 'subdecano' || $rolParaPermisos === 'subdecano/a')
            {{-- Subdecano/a: solo titulaciones --}}
            <a href="{{ route('titulaciones.index') }}" class="module-card">
                <i class="fas fa-certificate module-icon"></i>
                <div class="module-title">Titulaciones</div>
            </a>
        @elseif($rolParaPermisos === 'docente')
            {{-- Docente: solo titulaciones --}}
            <a href="{{ route('titulaciones.index') }}" class="module-card">
                <i class="fas fa-certificate module-icon"></i>
                <div class="module-title">Titulaciones</div>
            </a>
        @elseif(in_array($rolParaPermisos, ['coordinador','coordinadora','coordinador/a','abogado','abogada','abogado/a','estudiante']))
            {{-- Otros roles: solo titulaciones --}}
            <a href="{{ route('titulaciones.index') }}" class="module-card">
                <i class="fas fa-certificate module-icon"></i>
                <div class="module-title">Titulaciones</div>
            </a>
        @elseif(in_array($rolParaPermisos, ['secretario', 'secretaria', 'secretario_general', 'secretario/a', 'secretaria/o', 'secretario general', 'secretaria general']))
            {{-- Muestra todos los módulos administrativos --}}
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
        @else
            {{-- Otros roles: solo módulos básicos --}}
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
    <!-- <div style="background: #ffeeba; color: #856404; padding: 10px; margin-bottom: 10px;">
         Usuario: <strong>{{ $user->email ?? 'NO USER' }}</strong><br>
    Persona: <strong>{{ $persona ? $persona->email : 'NO PERSONA' }}</strong><br>
        Cargo detectado: <strong>{{ session('selected_role') ? session('selected_role') : ($cargo ?? '') }}</strong><br>
        <strong>Debug:</strong> Cargo original: {{ $cargo }} | Rol actual: {{ $rolActual }} | Rol alternativo: {{ $rolAlternativo }}
    </div>
    @if($rolAlternativo)
        <form method="POST" action="{{ route('role.select') }}" style="position: fixed; bottom: 30px; right: 30px; z-index: 999;">
            @csrf
            <input type="hidden" name="role" value="{{ $rolAlternativo }}">
            <button type="submit" class="btn btn-warning" style="padding: 12px 24px; font-weight: bold; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.12);">
                Cambiar a {{ ucfirst($rolAlternativo) }}
            </button>
        </form>
    @endif
</div> -->

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