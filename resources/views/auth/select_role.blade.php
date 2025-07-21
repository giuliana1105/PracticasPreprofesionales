@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Selecciona el rol con el que deseas operar</h2>
    <form method="POST" action="{{ route('role.select') }}">
        @csrf
        <div class="form-group">
            @foreach($roles as $rol)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="role_{{ $rol }}" value="{{ $rol }}" required>
                    <label class="form-check-label" for="role_{{ $rol }}">{{ ucfirst($rol) }}</label>
                </div>
            @endforeach
        </div>
        <button type="submit" class="btn btn-primary mt-3">Continuar</button>
    </form>
</div>
@endsection
