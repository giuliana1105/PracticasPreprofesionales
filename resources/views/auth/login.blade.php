<style>
    body {
        background-color: #e9ecef;
        color: #212529;
        margin: 0;
        font-family: sans-serif;
    }
    .header-container {
        background-color: #d32f2f;
        color: #fff;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        margin-bottom: 30px;
        border-radius: 5px;
        margin-top: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }
    .header-logo {
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
    .login-outer {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .login-card {
        width: 100%;
        max-width: 600px;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        background: #fff;
    }
    .login-card .card-body {
        padding: 48px 40px;
    }
    .btn-primary {
        background-color: #d32f2f;
        border-color: #d32f2f;
        border-radius: 5px;
    }
    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
    }
</style>

<div class="header-container">
    <div class="header-logo">
        <!-- Puedes poner aquí un logo si lo tienes -->
    </div>
    <div class="header-text-container">
        <span class="utn-text">UTN</span>
        <span class="ibarra-text">IBARRA - ECUADOR</span>
    </div>
</div>

<div class="login-outer">
    <div class="login-card card shadow">
        <div class="card-body">
            <h3 class="card-title text-center mb-4">Iniciar Sesión</h3>
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
</div>