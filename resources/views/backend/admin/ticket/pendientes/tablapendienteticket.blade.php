<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th  style="width: 3%">Fecha</th>
        <th  style="width: 3%">Fecha Registrado</th>
        <th  style="width: 15%">Tipo Incidente</th>
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
                {{ $dato->fechaRegistrado }}
            </td>
            <td>
                {{ $dato->tipo_incidente }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
