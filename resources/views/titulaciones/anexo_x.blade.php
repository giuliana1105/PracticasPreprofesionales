<!-- resources/views/pdf/anexo_x.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Anexo X</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: center; }
        th { background: #eee; }
        .pagenum:before { content: counter(page); }
        .pagecount:before { content: counter(pages); }
    </style>
</head>
<body>
    <table width="100%" style="margin-bottom: 10px; border: none;">
        <tr>
            <td width="15%" align="left" style="border: none;">
                <img src="{{ public_path('img/logo_ecuador.jpg') }}" alt="Escudo Ecuador" style="height:70px;">
            </td>
            <td width="70%" align="center" style="font-size:13px; border: none;">
                <strong>UNIVERSIDAD TÉCNICA DEL NORTE</strong><br>
                Acreditada Resolución Nro. 173-SE-33-CACES-2020<br>
                <strong>FACULTAD DE INGENIERÍA EN CIENCIAS APLICADAS</strong><br>
                SUBDECANATO
            </td>
            <td width="15%" align="right" style="border: none;">
                <img src="{{ public_path('img/logo_utn.jpg') }}" alt="Logo UTN" style="height:70px;">
            </td>
        </tr>
    </table>
    <p style="color:#003366; font-size:12px; margin-bottom:20px; text-align:left;">
        <span style="font-weight:bold; color:#003366;">ANEXO X.</span>
        <span style="color:#003366;">Evaluación de la Fase de Desarrollo del Informe Final del TIC. (tutorías de titulación)</span>
    </p>
    <p><strong>Tema:</strong> {{ $tema }}</p>
    <p><strong>Director:</strong> {{ $director }}</p>
    <p><strong>Asesor TIC:</strong> {{ $asesor_tic }}</p>
    <p><strong>Facultad:</strong> {{ $facultad }}</p>
    <p><strong>Carrera:</strong> {{ $carrera }}</p>
    <p><strong>Autor:</strong> {{ $autor }}</p>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">No.</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">Actividad</th>
                <th colspan="3" style="background:#eee;">CUMPLIÓ CRONOGRAMA</th>
                <th colspan="3" style="background:#eee;">RESULTADOS</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">HORAS<br>asesoría</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">OBSERVACIONES</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">FECHA</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle; width: 180px;">FIRMA</th>
            </tr>
            <tr>
                <th>MA</th>
                <th>A</th>
                <th>PA</th>
                <th>MA</th>
                <th>A</th>
                <th>PA</th>
            </tr>
        </thead>
        <tbody>
        @php
            $contador = 1;
        @endphp
        @foreach($actividades as $actividad)
            <tr>
                <td>{{ $contador }}</td>
                <td>{{ $actividad['actividad'] }}</td>
                <td>@if($actividad['cumplio'] == 'Muy Aceptable') X @endif</td>
                <td>@if($actividad['cumplio'] == 'Aceptable') X @endif</td>
                <td>@if($actividad['cumplio'] == 'Poco Aceptable') X @endif</td>
                <td>@if($actividad['resultados'] == 'Muy Aceptable') X @endif</td>
                <td>@if($actividad['resultados'] == 'Aceptable') X @endif</td>
                <td>@if($actividad['resultados'] == 'Poco Aceptable') X @endif</td>
                <td>{{ $actividad['horas'] }}</td>
                <td>{{ $actividad['observaciones'] }}</td>
                <td>{{ \Carbon\Carbon::parse($actividad['fecha'])->format('d/m/Y') }}</td>
                <td style="width: 180px;"></td>
            </tr>
            @php
                $contador++;
            @endphp
        @endforeach
        </tbody>
    </table>

    <br><br>
    <table width="100%" style="border: none; page-break-inside: avoid;">
        <tr>
            <td style="border: none; text-align: left;">
                Firma del estudiante: <span style="display:inline-block; width:250px; border-bottom:1px dotted #000;">&nbsp;</span>
            </td>
            <td style="border: none; text-align: left;">
                Firma del Coordinador de la carrera: <span style="display:inline-block; width:250px; border-bottom:1px dotted #000;">&nbsp;</span>
            </td>
        </tr>
    </table>
</body>
</html>