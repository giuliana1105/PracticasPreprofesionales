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
                <th rowspan="2" style="background:#eee; vertical-align: middle;">No.</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">Actividad</th>
                <th colspan="3" style="background:#eee;">CUMPLIÓ CRONOGRAMA</th>
                <th colspan="3" style="background:#eee;">RESULTADOS</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">HORAS<br>asesoría</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">OBSERVACIONES</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">FECHA</th>
                <th rowspan="2" style="background:#eee; vertical-align: middle;">FIRMA</th>
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
                <td>@if($cumplio == 'Muy Aceptable') X @endif</td>
                <td>@if($cumplio == 'Aceptable') X @endif</td>
                <td>@if($cumplio == 'Poco Aceptable') X @endif</td>
                <td>@if($resultados == 'Muy Aceptable') X @endif</td>
                <td>@if($resultados == 'Aceptable') X @endif</td>
                <td>@if($resultados == 'Poco Aceptable') X @endif</td>
                <td>{{ $horas }}</td>
                <td>{{ $observaciones }}</td>
                <td>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y H:i') }}</td>
                <td></td> {{-- Columna de firma vacía --}}
            </tr>
            @php $fila++; @endphp
        @endforeach
        </tbody>
    </table>
</body>
</html>