{{-- filepath: resources/views/titulaciones/pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $titulo }}</title>
    <style>
        body {
            background-color: #fff;
            color: #212529;
            font-family: Arial, sans-serif;
            margin: 10px 20px 10px 20px;
            font-size: 11px;
        }
        .container {
            width: 100%;
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
            align-items: flex-start; /* O 'center' si quieres centrado */
        }
        .utn-text {
            font-size: 1em;
            font-weight: bold;
        }
        .ibarra-text {
            font-size: 0.8em;
        }
        .page-title {
            background-color: #343a40;
            color: #fff;
            padding: 12px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 18px;
            font-size: 1.15em;
            font-weight: bold;
        }
        .table-container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
            margin-bottom: 15px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #dee2e6;
            padding: 5px 4px;
            text-align: center;
            font-size: 10px;
            word-break: break-word;
            white-space: normal;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        tr:nth-child(odd) {
            background-color: #f6f8fa;
        }
        tr:nth-child(even) {
            background-color: #fff;
        }
        .footer {
            text-align: right;
            color: #888;
            font-size: 10px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header-container">
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>
    <div class="page-title">{{ $titulo }}</div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Tema</th>
                    <th>Estudiante</th>
                    <th>Director</th>
                    <th>Asesor 1</th>
                    <th>Periodo</th>
                    <th>Estado</th>
                    <th>Fecha aprobación<br>(Consejo directivo)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($titulaciones as $tit)
                <tr>
                    <td>{{ $tit->tema }}</td>
                    <td>{{ $tit->estudiantePersona->nombres ?? '' }}</td>
                    <td>{{ $tit->directorPersona->nombres ?? '' }}</td>
                    <td>{{ $tit->asesor1Persona->nombres ?? '' }}</td>
                    <td>{{ $tit->periodo->periodo_academico ?? '' }}</td>
                    <td>{{ $tit->estado->nombre_estado ?? '' }}</td>
                    <td>
                        @php
                            $fechaConsejo = $tit->resTemas
                                ->filter(fn($resTema) =>
                                    isset($resTema->resolucion->tipoResolucion->nombre_tipo_res) &&
                                    strtolower($resTema->resolucion->tipoResolucion->nombre_tipo_res) === 'consejo directivo'
                                )
                                ->pluck('resolucion.fecha_res')
                                ->first();
                        @endphp
                        {{ $fechaConsejo ?? '' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="footer">
        Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</div>
</body>
</html>