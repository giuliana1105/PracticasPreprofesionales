@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Tema</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('temas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nombre_tema">Nombre(s) del Tema</label>
            <textarea class="form-control" id="nombre_tema" name="nombre_tema" rows="5" placeholder="Escribe un tema por línea" required></textarea>
            <small class="form-text text-muted">Puedes pegar varios temas, uno por línea. Cada tema inicia con una viñeta automáticamente.</small>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Tema(s)</button>
    </form>

    <div class="mt-3">
        <a href="{{ route('titulaciones.create') }}" class="btn btn-secondary">Crear Titulación</a>
    </div>

    @if(isset($temas) && $temas->count() > 0)
        <h2 class="mt-4">Temas Existentes</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Tema</th>
                    <th>Nro. Res</th>
                    <th>Tipo Res</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($temas as $tema)
                    @php
                        $resoluciones = $tema->resoluciones ?? [];
                    @endphp
                    @if(count($resoluciones))
                        @foreach($resoluciones as $i => $resolucion)
                            <tr>
                                @if($i === 0)
                                    <td rowspan="{{ count($resoluciones) }}">{{ $tema->id_tema }}</td>
                                    <td rowspan="{{ count($resoluciones) }}">{{ $tema->nombre_tema }}</td>
                                @endif
                                <td>{{ $resolucion->numero_res ?? '' }}</td>
                                <td>{{ $resolucion->tipoResolucion->nombre_tipo_res ?? '' }}</td>
                                @if($i === 0)
                                    <td rowspan="{{ count($resoluciones) }}">
                                        <a href="{{ route('temas.edit', $tema->id_tema) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('temas.destroy', $tema->id_tema) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este tema?')">Eliminar</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>{{ $tema->id_tema }}</td>
                            <td>{{ $tema->nombre_tema }}</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <a href="{{ route('temas.edit', $tema->id_tema) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('temas.destroy', $tema->id_tema) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este tema?')">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('nombre_tema');
    const bullet = '• ';
    // Si está vacío, agrega la primera viñeta
    if (textarea.value.trim() === '') {
        textarea.value = bullet;
    }
    textarea.addEventListener('keydown', function(e) {
        // Si presiona Enter
        if (e.key === 'Enter') {
            e.preventDefault();
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const value = this.value;
            // Inserta salto de línea y viñeta
            this.value = value.substring(0, start) + '\n' + bullet + value.substring(end);
            // Mueve el cursor después de la viñeta
            this.selectionStart = this.selectionEnd = start + 1 + bullet.length;
        }
    });
    // Evita borrar la primera viñeta
    textarea.addEventListener('input', function() {
        if (!this.value.startsWith(bullet)) {
            this.value = bullet + this.value.replace(/^(\s|•)*/, '');
        }
    });
});
</script>
@endsection