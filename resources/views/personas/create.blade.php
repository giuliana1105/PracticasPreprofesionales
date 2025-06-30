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
        max-width: 1200px;
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
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #495057;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 1em;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control:focus {
        border-color: #d32f2f;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.25);
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .invalid-feedback {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 5px;
    }

    select.form-control {
        height: auto;
    }

    .btn {
        display: inline-block;
        font-weight: 400;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        user-select: none;
        border: 1px solid transparent;
        padding: 10px 20px;
        font-size: 1em;
        line-height: 1.5;
        border-radius: 5px;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
    }

    .btn-primary {
        color: #fff;
        background-color: #d32f2f;
        border-color: #d32f2f;
    }

    .btn-primary:hover {
        background-color: #c82333;
        border-color: #c82333;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #5a6268;
    }

    .btn-success {
        color: #fff;
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
    }

    .nav-tabs {
        border-bottom: 1px solid #dee2e6;
    }

    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid transparent;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        padding: 10px 20px;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
    }

    .nav-tabs .nav-link.active {
        color: #d32f2f;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: bold;
    }

    .tab-content {
        padding: 20px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-top: none;
        border-radius: 0 0 5px 5px;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 5px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }

    .tab-switcher {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
    }
    .tab-btn {
        padding: 10px 24px;
        border: none;
        border-radius: 6px 6px 0 0;
        background: #f1f1f1;
        color: #333;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
        outline: none;
    }
    .tab-btn.active {
        background: #198754;
        color: #fff;
    }
    .tab-pane {
        display: none;
        background: #fff;
        padding: 24px 18px 18px 18px;
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .tab-pane.active {
        display: block;
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
            padding: 20px;
        }

        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 0.9em;
        }
    }
</style>

<div class="container">
    <div class="header-container">
        <div class="header-logo"></div>
        <div class="header-text-container">
            <span class="utn-text">UTN</span>
            <span class="ibarra-text">IBARRA - ECUADOR</span>
        </div>
    </div>

    <h1 class="page-title">Registrar Persona</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin-bottom: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="form-container">
        <div class="tab-switcher">
            <button class="tab-btn active" id="manual-tab" type="button">Ingreso Manual</button>
            <button class="tab-btn" id="csv-tab" type="button">Importar desde CSV</button>
        </div>

        <div class="tab-pane active" id="manual-pane">
            <form action="{{ route('personas.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="cedula" class="form-label">Cédula:</label>
                    <input type="text" name="cedula" class="form-control @error('cedula') is-invalid @enderror" 
                           value="{{ old('cedula') }}" required>
                    @error('cedula')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nombres" class="form-label">Nombres:</label>
                    <input type="text" name="nombres" class="form-control @error('nombres') is-invalid @enderror" 
                           value="{{ old('nombres') }}" required>
                    @error('nombres')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="apellidos" class="form-label">Apellidos:</label>
                    <input type="text" name="apellidos" id="apellidos"
                           class="form-control @error('apellidos') is-invalid @enderror"
                           value="{{ old('apellidos', $persona->apellidos ?? '') }}" required>
                    @error('apellidos')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="celular" class="form-label">Celular:</label>
                    <input type="text" name="celular" class="form-control @error('celular') is-invalid @enderror" 
                           value="{{ old('celular') }}" required>
                    @error('celular')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">email:</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="cargo">Cargo:</label>
                    <select id="cargo" name="cargo" class="form-control @error('cargo') is-invalid @enderror" required>
                        <option value="">Seleccione un cargo</option>
                        <option value="secretario_general" {{ old('cargo', $persona->cargo ?? '') == 'secretario_general' ? 'selected' : '' }}>Secretario General</option>
                        <option value="secretario" {{ old('cargo', $persona->cargo ?? '') == 'secretario' ? 'selected' : '' }}>Secretario/a</option>
                        <option value="abogado" {{ old('cargo', $persona->cargo ?? '') == 'abogado' ? 'selected' : '' }}>Abogado/a</option>
                        <option value="decano" {{ old('cargo', $persona->cargo ?? '') == 'decano' ? 'selected' : '' }}>Decano</option>
                        <option value="subdecano" {{ old('cargo', $persona->cargo ?? '') == 'subdecano' ? 'selected' : '' }}>Subdecano/a</option>
                        <option value="docente" {{ old('cargo', $persona->cargo ?? '') == 'docente' ? 'selected' : '' }}>Docente</option>
                        <option value="estudiante" {{ old('cargo', $persona->cargo ?? '') == 'estudiante' ? 'selected' : '' }}>Estudiante</option>
                        <option value="coordinador" {{ old('cargo', $persona->cargo ?? '') == 'coordinador' ? 'selected' : '' }}>Coordinador/a</option>
                    </select>
                    @error('cargo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" id="carreras-group">
                    <label for="carrera_id" class="form-label">Carrera(s):</label>
                    <div id="carreras-container">
                        @php
                            $oldCarreras = old('carrera_id', ['']);
                        @endphp
                        @foreach($oldCarreras as $i => $oldCarrera)
                        <div class="carrera-select-row mb-2 d-flex align-items-center">
                            <select name="carrera_id[]" class="form-control carrera-select" required>
                                <option value="">Seleccione una carrera</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id_carrera }}" {{ $oldCarrera == $carrera->id_carrera ? 'selected' : '' }}>
                                        {{ $carrera->siglas_carrera }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-success btn-sm add-carrera-btn ms-2" style="margin-left:8px;display:none;">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-sm remove-carrera-btn ms-2" style="margin-left:8px;display:none;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    @error('carrera_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <a href="{{ route('personas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>

        <div class="tab-pane" id="csv-pane">
            <h3 class="section-title" style="margin-top: 0;">Importar personas desde un archivo CSV</h3>
            <div class="alert alert-info">
                <strong>Formato requerido:</strong> El archivo debe contener las columnas: 
                cedula, nombres, apellidos, celular, email, sigla_carrera, cargo
            </div>
            <form action="{{ route('personas.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="archivo_csv" class="form-label">Archivo CSV:</label>
                    <input type="file" name="archivo_csv" class="form-control" accept=".csv" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-file-import"></i> Importar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const manualTab = document.getElementById('manual-tab');
        const csvTab = document.getElementById('csv-tab');
        const manualPane = document.getElementById('manual-pane');
        const csvPane = document.getElementById('csv-pane');

        manualTab.addEventListener('click', function() {
            manualTab.classList.add('active');
            csvTab.classList.remove('active');
            manualPane.classList.add('active');
            csvPane.classList.remove('active');
        });

        csvTab.addEventListener('click', function() {
            csvTab.classList.add('active');
            manualTab.classList.remove('active');
            csvPane.classList.add('active');
            manualPane.classList.remove('active');
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function toggleCarrerasMultiple() {
        const cargo = document.getElementById('cargo').value;
        const carrerasContainer = document.getElementById('carreras-container');
        const rows = carrerasContainer.querySelectorAll('.carrera-select-row');
        rows.forEach((row, idx) => {
            const addBtn = row.querySelector('.add-carrera-btn');
            const removeBtn = row.querySelector('.remove-carrera-btn');
            if (cargo === 'secretario' || cargo === 'coordinador') {
                addBtn.style.display = '';
                removeBtn.style.display = rows.length > 1 ? '' : 'none';
            } else {
                addBtn.style.display = 'none';
                removeBtn.style.display = 'none';
            }
        });
        // Si no es múltiple, deja solo la primera fila
        if (cargo !== 'secretario' && cargo !== 'coordinador') {
            while (carrerasContainer.children.length > 1) {
                carrerasContainer.removeChild(carrerasContainer.lastChild);
            }
        }
    }

    document.getElementById('cargo').addEventListener('change', toggleCarrerasMultiple);
    toggleCarrerasMultiple();

    // Botón para agregar más carreras
    document.getElementById('carreras-container').addEventListener('click', function(e) {
        if (e.target.closest('.add-carrera-btn')) {
            const row = e.target.closest('.carrera-select-row');
            const container = document.getElementById('carreras-container');
            const newRow = row.cloneNode(true);
            newRow.querySelector('select').value = '';
            container.appendChild(newRow);
            toggleCarrerasMultiple();
        }
        if (e.target.closest('.remove-carrera-btn')) {
            const row = e.target.closest('.carrera-select-row');
            const container = document.getElementById('carreras-container');
            if (container.children.length > 1) {
                row.remove();
                toggleCarrerasMultiple();
            }
        }
    });
});
</script>
@endpush
@endsection