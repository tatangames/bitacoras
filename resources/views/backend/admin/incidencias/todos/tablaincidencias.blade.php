<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="tabla" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th  style="width: 12%">Fecha</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    $(function () {
        $("#tabla").DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            pagingType: "full_numbers",
            order: [[0, 'desc']],   // orden por la 1ra columna (usa data-order)
            lengthMenu: [[500, -1], [500, "Todo"]],
            language: {
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                sSearch: "Buscar:",
                oPaginate: {
                    sFirst: "Primero",
                    sLast: "Último",
                    sNext: "Siguiente",
                    sPrevious: "Anterior"
                }
            },
            responsive: true
        });
    });
</script>
