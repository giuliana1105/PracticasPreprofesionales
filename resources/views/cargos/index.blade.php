@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cargos</h1>
    <a href="{{ route('cargos.create') }}" class="btn btn-primary">Crear Cargo</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Cargo</th>
                <th>Siglas</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cargos as $cargo)
                <tr>
                    <td>{{ $cargo->id_cargo }}</td>
                    <td>{{ $cargo->nombre_cargo }}</td>
                    <td>{{ $cargo->siglas_cargo }}</td>
                    <td>
                        <a href="{{ route('cargos.edit', $cargo->id_cargo) }}" class="btn btn-warning">Editar</a>
                        <form action="{{ route('cargos.destroy', $cargo->id_cargo) }}" method="POST" style="display:inline;">
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
