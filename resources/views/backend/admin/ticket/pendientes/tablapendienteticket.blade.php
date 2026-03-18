<table id="tabla" class="table table-bordered table-striped">
    <thead>
    <tr>
        <th  style="width: 4%">Fecha</th>
        <th  style="width: 4%">Estado</th>
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
                @php
                    $badges = [
                        0 => ['color' => 'warning',  'label' => 'Pendiente'],
                        1 => ['color' => 'primary',  'label' => 'En Proceso'],
                    ];
                    $badge = $badges[$dato->estado] ?? ['color' => 'secondary', 'label' => 'Desconocido'];
                @endphp
                <span class="badge bg-{{ $badge['color'] }}">{{ $badge['label'] }}</span>
            </td>

            <td>
                {{ $dato->tipo_incidente }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
