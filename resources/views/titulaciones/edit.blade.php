@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Titulación</h1>

    <form action="{{ route('titulaciones.update', $titulacion->id_titulacion) }}" method="POST" >
        @csrf
        @method('PUT')

        @include('titulaciones.form', ['titulacion' => $titulacion])

        <button type="submit" class="btn btn-primary mt-3">Actualizar</button>
    </form>
</div>
@endsection
