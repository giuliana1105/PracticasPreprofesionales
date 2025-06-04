@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<style>
    body {
        background-color: #e9ecef;
        color: #212529;
        margin: 0;
        font-family: sans-serif;
    }
    .container-login {
        max-width: 500px;
        margin: 40px auto 0 auto;
        padding: 0 15px;
    }
    .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        border-radius: 5px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    .header-logo {
        background-repeat: no-repeat;
        background-size: contain;
        height: 40px;
        width: auto;
        margin-right: 15px;
    }
    .header-text-container {
        display: flex;
        flex-direction: column;
    }
    .utn-text {
        font-size: 1.2em;
        font-weight: bold;
    }
    .ibarra-text {
        font-size: 0.9em;
    }
    .login-card {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        padding: 32px 28px;
    }
    .form-label {
        font-weight: 600;
        color: #d32f2f;
    }
    .form-control {
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }
    .form-control:focus {
        border-color: #d32f2f;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
    }
    .btn-primary {
        background-color: #d32f2f;
        border-color: #d32f2f;
        color: white;
        border-radius: 5px;
    }
    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
    }
    .text-center {
        text-align: center;
    }
    @media (max-width: 600px) {
        .container-login, .header-container {
            max-width: 100%;
            padding: 10px;
        }
        .login-card {
            padding: 18px 8px;
        }
    }
</style>

<div class="container-login">
    <div class="header-container">
        <div class="header-logo"></div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <div class="login-card">
        <h3 class="text-center mb-4">Iniciar Sesión</h3>
        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="mb-3">
                <label for="correo" class="form-label">Correo</label>
                <input type="email" name="correo" id="correo" class="form-control @error('correo') is-invalid @enderror" required autofocus>
                @error('correo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Cédula</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </div>
        </form>
    </div>
</div>
@endsection