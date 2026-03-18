<div class="mb-3 d-flex flex-wrap align-items-end" style="gap: 8px;">
    <div>
        <label class="mb-1 small font-weight-bold">Filtrar por Nombre</label>
        <input type="text" id="filtro-nombre" class="form-control form-control-sm" placeholder="Buscar nombre...">
    </div>
    <div>
        <label class="mb-1 small font-weight-bold">Filtrar por Rol</label>
        <select id="filtro-rol" class="form-control form-control-sm">
            <option value="">-- Todos --</option>
            @foreach($roles as $key => $value)
                <option value="{{ $value }}">{{ $value }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="mb-1 small font-weight-bold">Filtrar por Unidad</label>
        <select id="filtro-unidad" class="form-control form-control-sm">
            <option value="">-- Todas --</option>
            @foreach($arrayUnidad as $item)
                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <button id="btn-limpiar-filtros" class="btn btn-secondary btn-sm">
            <i class="fas fa-times"></i> Limpiar
        </button>
    </div>
</div>

<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Rol</th>
        <th>Usuario</th>
        <th>Unidad Asignada</th>
        <th>Opciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($usuarios as $dato)
        <tr>
            <td>{{ $dato->nombre }}</td>
            <td>{{ $dato->roles->implode('name', ', ') }}</td>
            <td>{{ $dato->usuario }}</td>
            <td>{{ $dato->nombreUnidad }}</td>
            <td>
                <button type="button" class="btn btn-primary btn-xs" onclick="verInformacion({{ $dato->id }})">
                    <i class="fas fa-pencil-alt" title="Editar"></i>&nbsp; Editar
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>
    // Evitar registrar el filtro múltiples veces si la tabla se recarga
    if (!window._filtroUsuariosRegistrado) {
        window._filtroUsuariosRegistrado = true;

        $.fn.dataTable.ext.search.push(function(settings, data) {
            if (settings.nTable.id !== 'tabla') return true;

            var nombre = $('#filtro-nombre').val().toLowerCase().trim();
            var rol    = $('#filtro-rol').val().toLowerCase().trim();
            var unidad = $('#filtro-unidad').val().toLowerCase().trim();

            var colNombre = data[0].toLowerCase();
            var colRol    = data[1].toLowerCase();
            var colUnidad = data[3].toLowerCase();

            if (nombre && !colNombre.includes(nombre)) return false;
            if (rol    && !colRol.includes(rol))       return false;
            if (unidad && !colUnidad.includes(unidad)) return false;

            return true;
        });
    }

    // Redibujar tabla al cambiar filtros
    $(document).off('keyup', '#filtro-nombre').on('keyup', '#filtro-nombre', function() {
        $('#tabla').DataTable().draw();
    });

    $(document).off('change', '#filtro-rol, #filtro-unidad').on('change', '#filtro-rol, #filtro-unidad', function() {
        $('#tabla').DataTable().draw();
    });

    // Limpiar filtros
    $(document).off('click', '#btn-limpiar-filtros').on('click', '#btn-limpiar-filtros', function() {
        $('#filtro-nombre').val('');
        $('#filtro-rol').val('');
        $('#filtro-unidad').val('');
        $('#tabla').DataTable().draw();
    });
</script>
