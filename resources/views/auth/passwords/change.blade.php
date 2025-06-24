@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #e9ecef;
        color: #212529;
        margin: 0;
        padding-bottom: 20px;
        font-family: sans-serif;
    }
    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }
    .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        border-radius: 5px;
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
    .page-title {
        background-color: #343a40;
        color: #fff;
        padding: 20px;
        text-align: center;
        border-radius: 5px;
        margin-bottom: 30px;
        font-size: 1.5em;
        font-weight: bold;
    }
    .form-container {
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 30px 30px 20px 30px;
        max-width: 500px;
        margin: 0 auto;
    }
    .btn-actualizar {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        background-color: #d32f2f;
        color: #fff;
        font-size: 1em;
        font-weight: 600;
        border: none;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.07);
        transition: background 0.2s, border 0.2s;
        cursor: pointer;
    }
    .btn-actualizar:hover {
        background-color: #c82333;
    }
    .btn-actualizar i {
        margin-right: 8px;
    }
    .form-label {
        font-weight: bold;
    }
    .custom-error {
        background-color: #fde8e8;
        border: 1px solid #f8b4b4;
        color: #b91c1c;
        padding: 16px 24px;
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
        text-align: left;
    }
    .custom-error button {
        position: absolute;
        top: 12px;
        right: 18px;
        background: none;
        border: none;
        color: #b91c1c;
        font-size: 18px;
        cursor: pointer;
    }
    @media (max-width: 768px) {
        .header-logo {
            height: 30px;
            margin-right: 10px;
        }
        .utn-text {
            font-size: 1em;
        }
        .ibarra-text {
            font-size: 0.8em;
        }
        .page-title {
            font-size: 1.3em;
            padding: 15px;
            margin-bottom: 20px;
        }
        .form-container {
            padding: 20px 10px 10px 10px;
        }
    }
</style>

<div class="container">
    <div class="header-container">
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Cambio de Contraseña</h1>

    @if ($errors->any())
        <div class="custom-error">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" onclick="this.parentElement.style.display='none';">
                &times;
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="custom-error">
            {{ session('error') }}
            <button type="button" onclick="this.parentElement.style.display='none';">
                &times;
            </button>
        </div>
    @endif

    <div class="form-container">
        <form method="POST" action="{{ route('password.change') }}">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Nueva contraseña</label>
                <input id="password" type="password" class="form-control" name="password" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar nueva contraseña</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn-actualizar">
                    <i class="fas fa-key"></i> Actualizar contraseña
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
@endpush
@endsection