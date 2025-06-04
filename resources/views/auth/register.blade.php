@extends('layouts.app')
@section('content')
<div class="container">
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <h2>Registrar usuario</h2>
        <input type="text" name="name" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="password_confirmation" placeholder="Confirmar contraseña" required>
        <select name="role" required>
            <option value="user">Usuario</option>
            <option value="admin">Administrador</option>
        </select>
        <button type="submit">Registrar</button>
        @error('email') <div>{{ $message }}</div> @enderror
    </form>
</div>
@endsection