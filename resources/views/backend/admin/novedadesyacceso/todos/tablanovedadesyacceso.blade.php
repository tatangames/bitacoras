<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th  style="width: 12%">Fecha</th>
        <th  style="width: 12%">Tipo Acceso</th>
        <th  style="width: 12%">Novedad</th>
        <th  style="width: 12%">Equipo</th>
        <th  style="width: 12%">Observaciones</th>
        <th  style="width: 8%">Opciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayBitacoraNovedadesAcceso as $dato)
        <tr>
            {{-- Usa timestamp para ordenar sin ambigüedad --}}
            <td data-order="{{ strtotime($dato->fecha) }}">
                {{ $dato->fechaFormat }}
            </td>

            <td>
                {{ $dato->nombreAcceso }}
            </td>
            <td>
                {{ $dato->novedad }}
            </td>
            <td>
                {{ $dato->equipo_involucrado }}
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
