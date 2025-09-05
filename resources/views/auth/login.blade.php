<x-guest-layout>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .login-container {
            max-width: 600px;
            margin: 0 auto;
            padding-top: 40px;
        }

        .login-header {
            background-color: #d32f2f;
            color: white;
            padding: 15px 30px;
            font-size: 1.5em;
            font-weight: bold;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .login-title {
            background-color: #343a40;
            color: white;
            font-size: 1.2em;
            padding: 15px 0;
            text-align: center;
            font-weight: bold; /* Negrita para el título */
        }

        .login-card {
            background-color: white;
            border-radius: 0 0 8px 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .login-footer {
            text-align: center;
            font-size: 0.9em;
            color: #6c757d;
            margin-top: 15px;
        }

        .btn-utn {
            background-color: #d32f2f;
            color: white;
            font-weight: bold;
            border: none;
            padding: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-utn:hover {
            background-color: #b71c1c;
        }

        .login-info {
            text-align: center;
            font-size: 0.9em;
            margin-bottom: 20px;
        }

        .form-check-label {
            font-size: 0.9em;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-height: 80px;
            width: auto;
        }

        /* Estilo para las etiquetas del formulario */
        .input-label {
            font-weight: bold;
            font-size: 1.2em; /* Negrita para correo y contraseña */
        }

        .login-input {
            font-size: 1em;
            padding: 10px 12px;
            width: 100%;
            min-width: 320px;
            max-width: 100%;
            box-sizing: border-box;
        }

        @media (max-width: 576px) {
            .login-card {
                padding: 20px;
            }

            .login-header,
            .login-title {
                font-size: 1.2em;
                padding: 10px;
            }

            .login-input {
                min-width: 0;
            }
        }
    </style>

    <!-- Login Box -->
    <div class="login-container">
        <!-- Contenedor del logo centrado -->
        <div class="logo-container">
            <img src="{{ asset('img/utn_logo.png') }}" alt="Logo UTN">
        </div>
        
        <!-- Título en barra gris (ya tiene negrita desde el CSS) -->
        <div class="login-title">
            Gestión de Titulaciones
        </div>

        <!-- Tarjeta de login -->
        <div class="login-card">
            <!-- Información de contacto -->
            <div class="login-info">
               
                      </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-3" :status="session('status')" />

            <!-- Formulario -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <x-input-label for="email" :value="__('Correo electrónico')" class="input-label" />
                    <x-text-input id="email" type="email" name="email" class="form-control login-input"
                        :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <x-input-label for="password" :value="__('Contraseña')" class="input-label" />
                    <x-text-input id="password" type="password" name="password" class="form-control login-input"
                        required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Recordarme -->
                <div class="form-check mb-4">
                    <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                    <label class="form-check-label" for="remember_me">
                        {{ __('Recuérdame') }}
                    </label>
                </div>

                <!-- Botón de inicio -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-utn">
                        {{ __('INICIAR SESIÓN') }}
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="login-footer mt-4">
                Universidad Técnica del Norte © {{ date('Y') }} - Todos los derechos reservados.
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @endpush
</x-guest-layout>