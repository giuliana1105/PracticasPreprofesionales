@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Carreras</h1>
    <a href="{{ route('carreras.create') }}" class="btn btn-primary">Crear Carrera</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre de la Carrera</th>
                <th>Siglas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($carreras as $carrera)
                <tr>
                    <td>{{ $carrera->id_carrera }}</td>
                    <td>{{ $carrera->nombre_carrera }}</td>
                    <td>{{ $carrera->siglas_carrera }}</td>
                    <td>
                        <a href="{{ route('carreras.edit', $carrera->id_carrera) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('carreras.destroy', $carrera->id_carrera) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
