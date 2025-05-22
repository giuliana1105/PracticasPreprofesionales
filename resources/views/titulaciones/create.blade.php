@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Titulación</h1>

 
    <form action="{{ route('titulaciones.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        

        <!-- Incluir el formulario desde form.blade.php -->
        @include('titulaciones.form')

        <button type="submit" class="btn btn-primary">Guardar Titulación</button>
    </form>
</div>
@endsection
