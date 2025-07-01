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
    </style>
</head>
<body>
    <h2 style="text-align:center;">ANEXO X</h2>
    <p><strong>Tema:</strong> {{ $tema }}</p>
    <p><strong>Director:</strong> {{ $director }}</p>
    <p><strong>Asesor TIC:</strong> {{ $asesor_tic }}</p>
    <p><strong>Facultad:</strong> {{ $facultad }}</p>
    <p><strong>Carrera:</strong> {{ $carrera }}</p>
    <p><strong>Autor:</strong> {{ $autor }}</p>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Actividad</th>
                <th>Cumplió cronograma<br>(MA/A/PA)</th>
                <th>Resultados<br>(MA/A/PA)</th>
                <th>Horas de asesoría</th>
                <th>Observaciones</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
        @php $fila = 1; @endphp
        @foreach($actividades as $fecha => $cambios)
            @php
                // Inicializa valores por defecto
                $actividad = '';
                $cumplio = '';
                $resultados = '';
                $horas = '';
                $observaciones = '';
                foreach($cambios as $cambio) {
                    switch($cambio->campo) {
                        case 'actividades_cronograma': $actividad = $cambio->valor_nuevo; break;
                        case 'cumplio_cronograma': $cumplio = $cambio->valor_nuevo; break;
                        case 'resultados': $resultados = $cambio->valor_nuevo; break;
                        case 'horas_asesoria': $horas = $cambio->valor_nuevo; break;
                        case 'observaciones': $observaciones = $cambio->valor_nuevo; break;
                    }
                }
            @endphp
            <tr>
                <td>{{ '1.' . $fila }}</td>
                <td>{{ $actividad }}</td>
                <td>
                    <span>@if($cumplio == 'Muy Aceptable') X @endif</span> MA
                    <span>@if($cumplio == 'Aceptable') X @endif</span> A
                    <span>@if($cumplio == 'Poco Aceptable') X @endif</span> PA
                </td>
                <td>
                    <span>@if($resultados == 'Muy Aceptable') X @endif</span> MA
                    <span>@if($resultados == 'Aceptable') X @endif</span> A
                    <span>@if($resultados == 'Poco Aceptable') X @endif</span> PA
                </td>
                <td>{{ $horas }}</td>
                <td>{{ $observaciones }}</td>
                <td>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</td>
            </tr>
            @php $fila++; @endphp
        @endforeach
        </tbody>
    </table>
</body>
</html>