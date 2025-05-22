<div class="form-group">
    <label for="tema">Tema</label>
    <select class="form-control" id="tema" name="tema" required>
        <option value="">Seleccione un tema</option>
        @foreach($temas as $tema)
            <option value="{{ $tema->id_tema }}">{{ $tema->nombre_tema }}</option>
        @endforeach
    </select>
</div>

<div class="form-group" id="resolucionesDiv" style="display: none;">
    <label>Resoluciones Asociadas</label>
    <ul id="listaResoluciones" class="list-group"></ul>
</div>

<script>
function mostrarResoluciones() {
    var selectTema = document.getElementById('tema');
    var selectedOption = selectTema.options[selectTema.selectedIndex];
    var resoluciones = selectedOption.getAttribute('data-resoluciones');
    var resolucionesDiv = document.getElementById('resolucionesDiv');
    var listaResoluciones = document.getElementById('listaResoluciones');
    
    if (resoluciones) {
        resoluciones = JSON.parse(resoluciones);
        listaResoluciones.innerHTML = '';

        resoluciones.forEach(function(resolucion) {
            var li = document.createElement('li');
            li.className = "list-group-item";
            // Cambia resolucion.tipo_res por resolucion.tipo_resolucion.nombre
            li.textContent = `Nro: ${resolucion.numero_res} | Tipo: ${resolucion.tipo_resolucion ? resolucion.tipo_resolucion.nombre_tipo_res : 'N/A'} | Aprobada: ${resolucion.fecha_res}`;
            listaResoluciones.appendChild(li);
        });

        resolucionesDiv.style.display = "block";
    } else {
        listaResoluciones.innerHTML = '';
        resolucionesDiv.style.display = "none";
    }
}
</script>

<div class="form-group">
    <label>Resoluciones Seleccionadas</label>
    <ul class="list-group">
        @forelse($resolucionesSeleccionadas as $resolucion)
            <li class="list-group-item">
                Nro: {{ $resolucion->numero_res }} | 
                Tipo: {{ $resolucion->tipoResolucion->nombre_tipo_res ?? 'N/A' }} | 
                Aprobada: {{ $resolucion->fecha_res }}
            </li>
        @empty
            <li class="list-group-item">No hay resoluciones seleccionadas.</li>
        @endforelse
    </ul>
</div>

<div class="form-group">
    <label for="estudiante">Estudiante</label>
    <select class="form-control" id="estudiante" name="estudiante" required>
        <option value="">Seleccione un estudiante</option>
        @foreach($estudiantes as $estudiante)
            <option value="{{ $estudiante->id }}">{{ $estudiante->nombres }} {{ $estudiante->apellidos }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="docente">Docente</label>
    <select class="form-control" id="docente" name="docente" required>
        <option value="">Seleccione un Docente</option>
        @foreach($docentes as $docente)
            <option value="{{ $docente->id }}">{{ $docente->nombres }} {{ $docente->apellidos }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="asesor1">Asesor 1</label>
    <select class="form-control" id="asesor1" name="asesor1" required>
        <option value="">Seleccione un Asesor</option>
        @foreach($docentes as $docente)
            <option value="{{ $docente->id }}">{{ $docente->nombres }} {{ $docente->apellidos }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="asesor2">Asesor 2</label>
    <select class="form-control" id="asesor2" name="asesor2">
        <option value="">Seleccione un Asesor (opcional)</option>
        @foreach($docentes as $docente)
            <option value="{{ $docente->id }}">{{ $docente->nombres }} {{ $docente->apellidos }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="periodo">Periodo</label>
    <select class="form-control" id="periodo" name="periodo" required>
        <option value="">Seleccione un periodo</option>
        @foreach($periodos as $periodo)
            <option value="{{ $periodo->id_periodo }}">{{ $periodo->periodo_academico }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="estado">Estado</label>
    <select class="form-control" id="estado" name="estado" required onchange="mostrarCampoActa()">
        <option value="">Seleccione un estado</option>
        @foreach($estados as $estado)
            <option value="{{ $estado->id_estado }}"
                {{ (old('estado', $titulacion->estado_id ?? '') == $estado->id_estado) ? 'selected' : '' }}>
                {{ $estado->nombre_estado }}
            </option>
        @endforeach
    </select>
</div>

<!-- Campo para Acta de Grado (Inicialmente oculto) -->
<div class="form-group" id="actaDiv" style="display: none;">
    <label for="acta_de_grado">Acta de Grado (PDF)</label>
    <input type="file" class="form-control" id="acta_de_grado" name="acta_de_grado" accept="application/pdf" disabled>
</div>

<div class="form-group">
    <label>Observaciones</label>
    <textarea name="observaciones" class="form-control">{{ old('observaciones', $titulacion->observaciones ?? '') }}</textarea>
</div>

<div class="form-group">
    <label>Avance (%)</label>
    <input type="number" name="avance" value="{{ old('avance', $titulacion->avance ?? '') }}" class="form-control" required>
</div>

<!-- Script para mostrar u ocultar el campo Acta -->
<script>
    function mostrarCampoActa() {
        var estadoSelect = document.getElementById("estado");
        var actaDiv = document.getElementById("actaDiv");
        var actaInput = document.getElementById("acta_de_grado");
        var estadoSeleccionado = estadoSelect.options[estadoSelect.selectedIndex].text.trim();

        if (estadoSeleccionado === "Graduado") {
            actaDiv.style.display = "block";
            actaInput.disabled = false;
        } else {
            actaDiv.style.display = "none";
            actaInput.disabled = true;
            actaInput.value = ""; // Limpia el campo si se oculta
        }
    }

    // Ejecutar al cargar la página también por si ya está seleccionado
    window.onload = function() {
        mostrarCampoActa();
    };
</script>