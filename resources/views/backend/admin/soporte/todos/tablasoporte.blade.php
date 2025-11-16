<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th  style="width: 12%">Fecha</th>
        <th  style="width: 12%">Unidad</th>
        <th  style="width: 12%">Descripción</th>
        <th  style="width: 12%">Solución</th>
        <th  style="width: 12%">Estado</th>
        <th  style="width: 12%">Observaciones</th>
        <th  style="width: 8%">Opciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayBitacoraSoporte as $dato)
        <tr>
            {{-- Usa timestamp para ordenar sin ambigüedad --}}
            <td data-order="{{ strtotime($dato->fecha) }}">
                {{ $dato->fechaFormat }}
            </td>

            <td>
                {{ $dato->nombreUnidad }}
            </td>
            <td>
                {{ $dato->descripcion }}
            </td>
            <td>
                {{ $dato->solucion }}
            </td>

            <td>
                @switch($dato->estado)
                    @case(1)
                        <span class="badge bg-danger">Pendiente</span>
                        @break
                    @case(2)
                        <span class="badge bg-success">Solucionado</span>
                        @break
                    @default
                        <span class="badge bg-secondary">Desconocido</span>
                @endswitch
            </td>

            <td>
                {{ $dato->observaciones }}
            </td>

            <td>
                <button type="button"
                        class="btn btn-info btn-xs"
                        onclick="informacion({{ $dato->id }})">
                    <i class="fas fa-edit" title="Editar"></i>&nbsp; Editar
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
