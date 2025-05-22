@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Lista de Temas</h2>
    <div class="mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Tema</th>
                    <th>Nro. Res</th>
                    <th>Tipo Res</th>
                    <!-- <th>Acciones</th> -->
                </tr>
            </thead>
            <tbody>
                @foreach($temas as $tema)
                    @php
                        $resoluciones = $tema->resoluciones ?? [];
                    @endphp
                    @if(count($resoluciones))
                        @foreach($resoluciones as $i => $resolucion)
                            <tr>
                                @if($i === 0)
                                    <td rowspan="{{ count($resoluciones) }}">{{ $tema->id_tema }}</td>
                                    <td rowspan="{{ count($resoluciones) }}">{{ $tema->nombre_tema }}</td>
                                @endif
                                <td>{{ $resolucion->numero_res ?? '' }}</td>
                                <td>{{ $resolucion->tipoResolucion->nombre_tipo_res ?? '' }}</td>
                                @if($i === 0)
                                    <td rowspan="{{ count($resoluciones) }}">
                                        <!-- <a href="{{ route('temas.edit', $tema->id_tema) }}" class="btn btn-warning btn-sm">Editar</a> -->
                                        <form action="{{ route('temas.destroy', $tema->id_tema) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este tema?')">Eliminar</button> -->
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>{{ $tema->id_tema }}</td>
                            <td>{{ $tema->nombre_tema }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <a href="{{ route('temas.edit', $tema->id_tema) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('temas.destroy', $tema->id_tema) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este tema?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
                <!-- <div class="mt-3">
                    <a href="{{ route('titulaciones.create') }}" class="btn btn-secondary">Crear Titulación</a>
                </div> -->
            </tbody>
        </table>
    </div>
</div>
@endsection
