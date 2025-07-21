@extends('layouts.app')
@section('content')
<style>
    .alineado-home {
        max-width: 1200px;
        margin: 24px auto 0 auto;
    }
    .alineado-home .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 24px 35px 18px 35px;
        border-radius: 8px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
    }
    .alineado-home .header-logo {
        background-repeat: no-repeat;
        background-size: contain;
        height: 40px;
        width: auto;
        margin-right: 15px;
    }
    .alineado-home .header-text-container {
        display: flex;
        flex-direction: column;
    }
    .alineado-home .utn-text {
        font-size: 1.5em;
        font-weight: bold;
    }
    .alineado-home .ibarra-text {
        font-size: 1.1em;
    }
    .alineado-home .page-title {
        background-color: #343a40;
        color: #fff;
        padding: 28px 0;
        text-align: center;
        border-radius: 8px;
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 0;
    }
    .select-role-container {
        background-color: #f8f9fa;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        padding: 32px 24px;
        margin-top: 40px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }
    .select-role-title {
        font-size: 1.3em;
        font-weight: bold;
        color: #d32f2f;
        margin-bottom: 18px;
        text-align: center;
    }
    .form-check {
        margin-bottom: 18px;
        font-size: 1.1em;
    }
    .form-check-input {
        margin-right: 10px;
        accent-color: #d32f2f;
    }
    .btn-primary {
        background-color: #d32f2f;
        border-color: #d32f2f;
        color: white;
        font-weight: bold;
        border-radius: 5px;
        padding: 10px 24px;
        font-size: 1.1em;
        margin-top: 18px;
        width: 100%;
    }
    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
        color: white;
    }
    @media (max-width: 768px) {
        .alineado-home { max-width: 98vw; }
        .alineado-home .header-container, .alineado-home .page-title {
            padding: 12px 8px;
            font-size: 1em;
        }
        .alineado-home .page-title { font-size: 1.1em; }
        .select-role-container { padding: 16px 8px; }
    }
</style>

<div class="alineado-home">
    <div class="header-container">
        <div class="header-logo"></div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>
    <div class="page-title">PORTAFOLIOS</div>
    <div class="select-role-container">
        <div class="select-role-title">Selecciona el rol con el que deseas operar</div>
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
            <button type="submit" class="btn btn-primary">Continuar</button>
        </form>
    </div>
</div>
@endsection
