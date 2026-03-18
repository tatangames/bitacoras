<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th style="width: 10%">Fecha</th>
        <th style="width: 12%">Usuario</th>
        <th style="width: 12%">Equipo</th>
        <th style="width: 12%">Tipo Mantenimiento</th>
        <th style="width: 18%">Descripción</th>
        <th style="width: 10%">Próximo Mant.</th>
        <th style="width: 14%">Observaciones</th>
        <th style="width: 8%">Opciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayBitacoraMantenimiento as $dato)
        <tr>
            <td data-order="{{ strtotime($dato->fecha) }}">
                {{ $dato->fechaFormat }}
            </td>
            <td>{{ $dato->nombreUsuario }}</td>
            <td>{{ $dato->equipo }}</td>
            <td>
                @switch($dato->tipo_mantenimiento)
                    @case(1)
                        <span class="badge badge-info">Actualización</span>
                        @break
                    @case(2)
                        <span class="badge badge-warning">Preventivo</span>
                        @break
                    @case(3)
                        <span class="badge badge-danger">Correctivo</span>
                        @break
                    @default
                        <span class="badge badge-secondary">Desconocido</span>
                @endswitch
            </td>
            <td>{{ $dato->descripcion }}</td>
            <td>{{ $dato->fechaProximo }}</td>
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
