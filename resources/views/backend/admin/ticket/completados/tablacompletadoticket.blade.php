<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th  style="width: 12%">Fecha</th>
        <th  style="width: 12%">Unidad</th>
        <th  style="width: 12%">Tipo Incidente</th>
        <th  style="width: 12%">Sistema Afectado</th>
        <th  style="width: 12%">Nivel</th>
        <th  style="width: 12%">Medida Correctiva</th>
        <th  style="width: 12%">Observaciones</th>
        <th  style="width: 8%">Opciones</th>
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
                {{ $dato->nombreUnidad }}
            </td>
            <td>
                {{ $dato->tipo_incidente }}
            </td>
            <td>
                {{ $dato->sistema_afectado }}
            </td>
            <td>
                @switch($dato->nivel)
                    @case(1)
                        <span class="badge bg-success">Ordinarios</span>
                        @break

                    @case(2)
                        <span class="badge bg-warning text-dark">Relevantes</span>
                        @break

                    @case(3)
                        <span class="badge bg-danger">Críticos</span>
                        @break

                    @default
                        <span class="badge bg-secondary">Desconocido</span>
                @endswitch
            </td>

            <td>
                {{ $dato->medida_correctivas }}
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
