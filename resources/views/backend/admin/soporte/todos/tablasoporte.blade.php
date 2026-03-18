<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th style="width: 10%">Fecha</th>
        <th style="width: 12%">Usuario</th>
        <th style="width: 12%">Unidad</th>
        <th style="width: 18%">Descripción</th>
        <th style="width: 18%">Solución</th>
        <th style="width: 8%">Estado</th>
        <th style="width: 14%">Observaciones</th>
        <th style="width: 8%">Opciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayBitacoraSoporte as $dato)
        <tr>
            <td data-order="{{ strtotime($dato->fecha) }}">
                {{ $dato->fechaFormat }}
            </td>
            <td>{{ $dato->nombreUsuario }}</td>
            <td>{{ $dato->nombreUnidad }}</td>
            <td>{{ $dato->descripcion }}</td>
            <td>{{ $dato->solucion }}</td>
            <td class="text-center">
                @switch($dato->estado)
                    @case(1)
                        <span class="badge badge-danger">Pendiente</span>
                        @break
                    @case(2)
                        <span class="badge badge-success">Solucionado</span>
                        @break
                    @default
                        <span class="badge badge-secondary">Desconocido</span>
                @endswitch
            </td>
            <td>{{ $dato->observaciones }}</td>
            <td>
                <button type="button"
                        class="btn btn-info btn-xs"
                        onclick="informacion({{ $dato->id }})">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
