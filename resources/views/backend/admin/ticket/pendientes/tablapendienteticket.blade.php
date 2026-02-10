<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th  style="width: 4%">Fecha</th>
        <th  style="width: 12%">Tipo Incidente</th>
        <th  style="width: 5%">Opciones</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayBitacoraIncidencias as $dato)
        <tr>
            {{-- Usa timestamp para ordenar sin ambigüedad --}}
            <td data-order="{{ strtotime($dato->fecha) }}">
                {{ $dato->fechaFormat }}
            </td>
            <td>
                {{ $dato->tipo_incidente }}
            </td>

            <td>
                <button type="button"
                        class="btn btn-success btn-xs"
                        onclick="informacion({{ $dato->id }})">
                    <i class="fas fa-edit" title="Solucionado"></i>&nbsp; Solucionado
                </button>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
