@extends('adminlte::page')

@section('title', 'Lista - Mantenimiento')

@section('content_header')
    <h1>Lista - Mantenimiento</h1>
@stop

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Sweetalert2', true)

@include('backend.urlglobal')

@section('content_top_nav_right')
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet"/>
    <link href="{{ asset('css/select2.min.css') }}" type="text/css" rel="stylesheet">
    <link href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}" type="text/css" rel="stylesheet">

    <li class="nav-item dropdown">
        <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-cogs"></i>
            <span class="d-none d-md-inline">{{ Auth::guard('admin')->user()->nombre }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a href="{{ route('admin.perfil') }}" class="dropdown-item">
                <i class="fas fa-user mr-2"></i> Editar Perfil
            </a>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                </button>
            </form>
        </div>
    </li>
@endsection

@section('content')

    <div id="divcontenedor">

        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <label>Usuario: {{ $infoUsuario->nombre }}</label>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Bitacoras</li>
                        <li class="breadcrumb-item active">Listado de Mantenimientos</li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-gray-dark">
                    <div class="card-header">
                        <h3 class="card-title">Listado de Mantenimientos</h3>
                    </div>
                    <div class="card-body">

                        {{-- FILTROS --}}
                        <div class="card card-outline card-secondary mb-3">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filtros</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Fecha Desde</label>
                                            <input type="date" id="filtro-desde" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Fecha Hasta</label>
                                            <input type="date" id="filtro-hasta" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="font-weight-bold">Tipo Mantenimiento</label>
                                            <select id="filtro-tipo" class="form-control form-control-sm">
                                                <option value="">-- Todos --</option>
                                                <option value="1">Actualización</option>
                                                <option value="2">Preventivo</option>
                                                <option value="3">Correctivo</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <div class="form-group w-100">
                                            <button class="btn btn-primary btn-sm mr-2" onclick="aplicarFiltros()">
                                                <i class="fas fa-search"></i> Filtrar
                                            </button>
                                            <button class="btn btn-secondary btn-sm" onclick="limpiarFiltros()">
                                                <i class="fas fa-times"></i> Limpiar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TABLA --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div id="tablaDatatable"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <!-- Modal Editar -->
        <div class="modal fade" id="modalEditar">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Editar Registro</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formulario-editar">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <div class="form-group">
                                            <input type="hidden" id="id-editar">
                                        </div>

                                        <div class="form-group" style="width: 30%">
                                            <label>Fecha <span style="color: red">*</span></label>
                                            <input type="date" class="form-control" id="fechahora-nuevo">
                                        </div>

                                        <div class="form-group">
                                            <label>Equipo</label>
                                            <input type="text" class="form-control" id="equipo-nuevo" placeholder="Equipo involucrado">
                                        </div>

                                        <div class="form-group">
                                            <label>Tipo Mantenimiento</label>
                                            <select class="form-control" id="select-mantenimiento">
                                                <option value="1">Actualización</option>
                                                <option value="2">Preventivo</option>
                                                <option value="3">Correctivo</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Descripción</label>
                                            <input type="text" class="form-control" id="descripcion-nuevo" placeholder="">
                                        </div>

                                        <div class="form-group">
                                            <label>Próximo Mantenimiento</label>
                                            <input type="date" class="form-control" id="proximo-nuevo">
                                        </div>

                                        <div class="form-group">
                                            <label>Observaciones</label>
                                            <input type="text" class="form-control" id="observacion-nuevo" placeholder="Observaciones">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        <button type="button"
                                style="font-weight: bold; background-color: #28a745; color: white !important;"
                                id="btn-guardar"
                                class="btn btn-success btn-sm"
                                onclick="editar()">
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

@stop

@section('js')
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script>
        $(function () {

            function initDataTable() {
                if ($.fn.DataTable.isDataTable('#tabla')) {
                    $('#tabla').DataTable().destroy();
                    $('#tabla').off();
                }

                if ($('#tabla').length === 0) return;

                $('#tabla').DataTable({
                    paging: true,
                    lengthChange: true,
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: false,
                    responsive: true,
                    pagingType: "full_numbers",
                    lengthMenu: [[100, 150, -1], [100, 150, "Todo"]],
                    language: {
                        sProcessing: "Procesando...",
                        sLengthMenu: "Mostrar _MENU_ registros",
                        sZeroRecords: "No se encontraron resultados",
                        sEmptyTable: "Ningún dato disponible en esta tabla",
                        sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                        sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
                        sInfoFiltered: "(filtrado de _MAX_ registros)",
                        sSearch: "Buscar:",
                        oPaginate: {
                            sFirst: "Primero",
                            sLast: "Último",
                            sNext: "Siguiente",
                            sPrevious: "Anterior"
                        },
                        oAria: {
                            sSortAscending: ": Orden ascendente",
                            sSortDescending: ": Orden descendente"
                        }
                    },
                    dom:
                        "<'row align-items-center'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-md-right'f>>" +
                        "tr" +
                        "<'row align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                });

                $('#tabla_length select').addClass('form-control form-control-sm');
                $('#tabla_filter input').addClass('form-control form-control-sm').css('display', 'inline-block');
            }

            function cargarTabla(params = {}) {
                const ruta = "{{ url('/admin/bitacora/lista/mantenimiento/tabla') }}";

                if ($.fn.DataTable.isDataTable('#tabla')) {
                    $('#tabla').DataTable().destroy();
                    $('#tabla').off();
                }

                $.ajax({
                    url: ruta,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ...params
                    },
                    success: function (html) {
                        $('#tablaDatatable').html(html);
                        setTimeout(function () {
                            initDataTable();
                        }, 100);
                    },
                    error: function (xhr) {
                        console.error('Error al cargar tabla:', xhr.status, xhr.statusText);
                    }
                });
            }

            // Primera carga
            cargarTabla();

            window.recargar = function () {
                cargarTabla({
                    fecha_desde:       $('#filtro-desde').val(),
                    fecha_hasta:       $('#filtro-hasta').val(),
                    tipo_mantenimiento: $('#filtro-tipo').val(),
                });
            };

            window.aplicarFiltros = function () {
                cargarTabla({
                    fecha_desde:       $('#filtro-desde').val(),
                    fecha_hasta:       $('#filtro-hasta').val(),
                    tipo_mantenimiento: $('#filtro-tipo').val(),
                });
            };

            window.limpiarFiltros = function () {
                $('#filtro-desde').val('');
                $('#filtro-hasta').val('');
                $('#filtro-tipo').val('');
                cargarTabla();
            };
        });
    </script>

    <script>
        function informacion(id) {
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(urlAdmin + '/admin/bitacora/mantenimiento/informacion', { 'id': id })
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);
                        $('#fechahora-nuevo').val(response.data.info.fecha);
                        $('#equipo-nuevo').val(response.data.info.equipo);
                        $('#descripcion-nuevo').val(response.data.info.descripcion);
                        $('#proximo-nuevo').val(response.data.info.proximo_mantenimiento);
                        $('#observacion-nuevo').val(response.data.info.observaciones);
                        document.getElementById('select-mantenimiento').value = response.data.info.tipo_mantenimiento;
                    } else {
                        toastr.error('Información no encontrada');
                    }
                })
                .catch(() => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function editar() {
            var id           = document.getElementById('id-editar').value;
            var fecha        = document.getElementById('fechahora-nuevo').value;
            var equipo       = document.getElementById('equipo-nuevo').value;
            var mantenimiento = document.getElementById('select-mantenimiento').value;
            var descripcion  = document.getElementById('descripcion-nuevo').value;
            var proximo      = document.getElementById('proximo-nuevo').value;
            var observacion  = document.getElementById('observacion-nuevo').value;

            if (fecha === '') {
                toastr.error('Fecha es requerida');
                return;
            }

            const btnGuardar = document.getElementById('btn-guardar');
            btnGuardar.disabled = true;
            btnGuardar.textContent = 'Guardando...';

            openLoading();

            var formData = new FormData();
            formData.append('id', id);
            formData.append('fecha', fecha);
            formData.append('equipo', equipo);
            formData.append('mantenimiento', mantenimiento);
            formData.append('descripcion', descripcion);
            formData.append('proximo', proximo);
            formData.append('observacion', observacion);

            axios.post(urlAdmin + '/admin/bitacora/mantenimiento/actualizar', formData)
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar();
                    } else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch(() => {
                    toastr.error('Error al registrar');
                    closeLoading();
                })
                .finally(() => {
                    btnGuardar.disabled = false;
                    btnGuardar.textContent = 'Actualizar';
                });
        }
    </script>
@endsection
