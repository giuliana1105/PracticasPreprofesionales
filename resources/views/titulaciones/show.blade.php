@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #fff;
        color: #212529;
        font-family: Arial, sans-serif;
        margin: 10px 20px 10px 20px;
        font-size: 13px;
    }
    .container {
        max-width: 900px;
        margin: 0 auto;
    }
    .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        border-radius: 5px;
    }
    .header-text-container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }
    .utn-text {
        font-size: 1.1em;
        font-weight: bold;
    }
    .ibarra-text {
        font-size: 0.9em;
    }
    .page-title {
        background-color: #343a40;
        color: #fff;
        padding: 14px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 18px;
        font-size: 1.2em;
        font-weight: bold;
    }
    .details-table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 5px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.06);
        margin-bottom: 20px;
    }
    .details-table th, .details-table td {
        border: 1px solid #dee2e6;
        padding: 8px 6px;
        text-align: left;
        font-size: 13px;
        vertical-align: top;
        word-break: break-word;
        white-space: normal;
    }
    .details-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        width: 30%;
    }
    .details-table tr:nth-child(odd) {
        background-color: #f6f8fa;
    }
    .details-table tr:nth-child(even) {
        background-color: #fff;
    }
    .btn {
        min-width: 36px;
        border-radius: 5px;
        padding: 6px 12px;
        text-decoration: none;
        display: inline-block;
        text-align: center;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    .btn-outline-primary {
        color: #d32f2f;
        border: 1px solid #d32f2f;
        background: #fff;
    }
    .btn-outline-primary:hover {
        background-color: #d32f2f;
        color: #fff;
    }
    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
        color: white;
    }
    .btn-info {
        background-color: #17a2b8 !important;
        border-color: #17a2b8 !important;
        color: #fff !important;
        font-weight: 600;
    }
    .btn-info:hover {
        background-color: #138496 !important;
        border-color: #138496 !important;
        color: #fff !important;
    }
    .mb-3 { margin-bottom: 1rem; }
    
    .mt-2 { margin-top: 0.5rem; }
    .fw-bold { font-weight: bold; }
    .text-muted { color: #888; }
    hr { margin: 10px 0; }
</style>
@php
    $user = auth()->user();
    $persona = $user ? \App\Models\Persona::where('email', $user->email)->first() : null;
    $cargo = strtolower(trim($persona->cargo ?? ''));
    $esEstudiante = $cargo === 'estudiante';
    $esDocente = $cargo === 'docente';
    $esCoordinador = in_array($cargo, ['coordinador', 'coordinadora', 'coordinador/a','docente-coordinador/a']);
    $esDecano = in_array($cargo, ['decano', 'decana']);
    $esSubdecano = in_array($cargo, ['subdecano', 'subdecana' ]);
    $esAbogado = in_array($cargo, ['abogado', 'abogada']);
    $esSoloLectura = $esDecano || $esSubdecano || $esAbogado;
    $esSecretarioGeneral = $cargo === 'secretario_general';
    $esSecretaria = in_array($cargo, ['secretario', 'secretaria']);
    // Para secretaria, obtener solo resoluciones de sus carreras asignadas
    $carrerasSecretaria = $esSecretaria && $persona ? $persona->carreras()->pluck('id_carrera')->toArray() : [];
@endphp
@if($esEstudiante && $titulacion->cedula_estudiante !== ($persona->cedula ?? null))
    <div class="alert alert-danger">No autorizado para ver esta titulación.</div>
    @php exit; @endphp
@endif

<div class="container">
    <div class="header-container">
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>
    <div class="page-title">Detalles de Titulación</div>
    <a href="{{ route('titulaciones.index') }}" class="btn btn-secondary mb-3">Volver</a>
    @if(!$esEstudiante && !$esSecretarioGeneral && !$esSoloLectura && !$esCoordinador)
        <a href="#" id="btn-anexo-x" class="btn btn-info mb-3" style="margin-left: 8px;">
            <i class="fas fa-file-pdf"></i> Anexo X
        </a>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('btn-anexo-x').addEventListener('click', function(e) {
                    e.preventDefault();
                    window.open('/titulaciones/{{ $titulacion->id_titulacion }}/anexo-x', '_blank');
                });
            });
        </script>
    @endif
    <table class="details-table">
        <tr>
            <th>Tema</th>
            <td>{{ $titulacion->tema }}</td>
        </tr>
        <tr>
            <th>Estudiante</th>
            <td>{{ $titulacion->estudiantePersona->nombres ?? '' }} {{ $titulacion->estudiantePersona->apellidos ?? '' }}</td>
        </tr>
        <tr>
            <th>Cédula Estudiante</th>
            <td>{{ $titulacion->cedula_estudiante }}</td>
        </tr>
        <tr>
            <th>Carrera</th>
            <td>
                @if($titulacion->estudiantePersona && $titulacion->estudiantePersona->carreras && $titulacion->estudiantePersona->carreras->count())
                    @foreach($titulacion->estudiantePersona->carreras as $carrera)
                        <div>{{ $carrera->siglas_carrera }}</div>
                    @endforeach
                @elseif($titulacion->estudiantePersona && $titulacion->estudiantePersona->carrera)
                    {{ $titulacion->estudiantePersona->carrera->siglas_carrera }}
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>Director</th>
            <td>{{ $titulacion->directorPersona->nombres ?? '' }} {{ $titulacion->directorPersona->apellidos ?? '' }}</td>
        </tr>
        <tr>
            <th>Cédula Director</th>
            <td>{{ $titulacion->cedula_director }}</td>
        </tr>
        <tr>
            <th>Asesor 1</th>
            <td>{{ $titulacion->asesor1Persona->nombres ?? '' }} {{ $titulacion->asesor1Persona->apellidos ?? '' }}</td>
        </tr>
        <tr>
            <th>Cédula Asesor 1</th>
            <td>{{ $titulacion->cedula_asesor1 }}</td>
        </tr>
        <tr>
            <th>Periodo</th>
            <td>{{ $titulacion->periodo->periodo_academico ?? '' }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            <td>{{ $titulacion->estado->nombre_estado ?? '' }}</td>
        </tr>
        <tr>
            <th>Avance</th>
            <td>{{ $titulacion->avance }}%</td>
        </tr>
        <tr>
            <th>Observaciones</th>
            <td>{{ $titulacion->observaciones }}</td>
        </tr>
        <tr>
            <th>Resoluciones</th>
            <td>
                @foreach($titulacion->resTemas as $resTema)
                    @php
                        // Si es secretaria, solo mostrar resoluciones de sus carreras asignadas
                        $mostrarResolucion = true;
                        if ($esSecretaria && !empty($carrerasSecretaria)) {
                            $mostrarResolucion = in_array($resTema->resolucion->carrera_id ?? null, $carrerasSecretaria);
                        }
                    @endphp
                    @if($mostrarResolucion)
                        <div class="mb-2">
                            <span class="fw-bold">Tipo:</span> {{ $resTema->resolucion->tipoResolucion->nombre_tipo_res ?? '' }}<br>
                            <span class="fw-bold">Número:</span> {{ $resTema->resolucion->numero_res ?? '' }}<br>
                            <span class="fw-bold">Fecha aprobación:</span> {{ $resTema->resolucion->fecha_res ?? '' }}<br>
                            @if(!empty($resTema->resolucion->archivo_pdf))
                                <a href="{{ asset('storage/' . $resTema->resolucion->archivo_pdf) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-1">
                                    <i class="fas fa-file-pdf"></i> Ver PDF
                                </a>
                            @else
                                <span class="text-muted">Sin PDF</span>
                            @endif
                        </div>
                        <hr>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>Acta de grado</th>
            <td>
                @if(
                    $titulacion->estado &&
                    strtolower($titulacion->estado->nombre_estado) === 'graduado' &&
                    $titulacion->acta_grado
                )
                    <a href="{{ asset('storage/' . $titulacion->acta_grado) }}" target="_blank" class="btn btn-outline-primary btn-sm" style="border: 1px solid #d32f2f;">
                        <i class="fas fa-file-pdf"></i> Ver acta de grado
                    </a>
                @else
                    <span class="text-muted">No disponible</span>
                @endif
            </td>
        </tr>
        @if(!$esSecretarioGeneral)
            <tr>
                <th>Cambios</th>
                <td>
                    <button id="btn-ver-cambios" class="btn btn-success btn-sm mb-2" type="button" onclick="mostrarCambios()" style="display: inline-block;">
                        <i class="fas fa-eye"></i> Ver más
                    </button>
                    <button id="btn-ocultar-cambios" class="btn btn-success btn-sm mb-2" type="button" onclick="ocultarCambios()" style="display: none;">
                        <i class="fas fa-eye-slash"></i> Ver menos
                    </button>
                    <div id="tabla-cambios" style="display: none;">
                        @if($titulacion->avanceHistorial && count($titulacion->avanceHistorial))
                            <table style="width:100%; font-size:13px; border-collapse:collapse;">
                                <thead>
                                    <tr style="background:#f8f9fa;">
                                        <th style="padding:8px; border-bottom:1px solid #dee2e6; min-width:110px;">Fecha</th>
                                        <th style="padding:8px; border-bottom:1px solid #dee2e6; min-width:120px;">Docente</th>
                                        <th style="padding:8px; border-bottom:1px solid #dee2e6; min-width:120px;">Campo</th>
                                        <th style="padding:8px; border-bottom:1px solid #dee2e6; min-width:120px;">Valor anterior</th>
                                        <th style="padding:8px; border-bottom:1px solid #dee2e6; min-width:120px;">Valor nuevo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($titulacion->avanceHistorial->sortByDesc('created_at') as $historial)
                                        <tr>
                                            <td style="padding:8px; border-bottom:1px solid #f1f1f1;">{{ $historial->created_at->format('d/m/Y H:i') }}</td>
                                            <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                                {{ $historial->docente->nombres ?? '' }} {{ $historial->docente->apellidos ?? '' }}
                                            </td>
                                            <td style="padding:8px; border-bottom:1px solid #f1f1f1;">
                                                @switch($historial->campo)
                                                    @case('avance') Avance (%) @break
                                                    @case('observaciones') Observaciones @break
                                                    @case('actividades_cronograma') Actividades según el cronograma @break
                                                    @case('cumplio_cronograma') Cumplió el cronograma @break
                                                    @case('resultados') Resultados @break
                                                    @case('horas_asesoria') Horas de asesoría @break
                                                    @default {{ ucfirst($historial->campo) }}
                                                @endswitch
                                            </td>
                                            <td style="padding:8px; border-bottom:1px solid #f1f1f1; white-space:pre-line;">
                                                {{ trim($historial->valor_anterior) !== '' ? $historial->valor_anterior : 'Sin valor' }}
                                            </td>
                                            <td style="padding:8px; border-bottom:1px solid #f1f1f1; white-space:pre-line;">{{ $historial->valor_nuevo }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <span class="text-muted">Sin cambios registrados</span>
                        @endif
                    </div>
                    <script>
                        function mostrarCambios() {
                            document.getElementById('tabla-cambios').style.display = 'block';
                            document.getElementById('btn-ver-cambios').style.display = 'none';
                            document.getElementById('btn-ocultar-cambios').style.display = 'inline-block';
                        }
                        function ocultarCambios() {
                            document.getElementById('tabla-cambios').style.display = 'none';
                            document.getElementById('btn-ver-cambios').style.display = 'inline-block';
                            document.getElementById('btn-ocultar-cambios').style.display = 'none';
                        }
                    </script>
                </td>
            </tr>
        @endif
        @if($esDocente && !$esSoloLectura && !$esCoordinador)
            <tr>
                
            </tr>
        @endif
    </table>
</div>
@endsection