@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1>Roles</h1>
@stop
{{-- Activa plugins que necesitas --}}
    @section('plugins.Datatables', true)
    @section('plugins.DatatablesPlugins', true)
    @section('plugins.Toastr', true)
    @section('plugins.Sweetalert2', true)

    @section('content_top_nav_right')
        <li class="nav-item dropdown">
            <a href="#" class="nav-link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cogs"></i>
                <span class="d-none d-md-inline">{{ Auth::user()->nombre ?? 'Usuario' }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ route('admin.perfil') }}" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Editar Perfil
                </a>

                <div class="dropdown-divider"></div>

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
                <div class="container-fluid">
                    <button type="button" onclick="modalAgregar()" class="btn btn-success btn-sm">
                        <i class="fas fa-pencil-alt"></i>
                        Nuevo Rol
                    </button>

                    <button type="button" onclick="vistaPermisos()" class="btn btn-success btn-sm">
                        <i class="fas fa-list-alt"></i>
                        Lista de Permisos
                    </button>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="card card-gray-dark">
                        <div class="card-header">
                            <h3 class="card-title">Lista</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="tablaDatatable"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="modalAgregar">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Nuevo Rol</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formulario-nuevo">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <div class="form-group">
                                                <label>Nombre</label>
                                                <input type="text" maxlength="30" class="form-control" id="nombre-nuevo" placeholder="Nombre">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-success" onclick="agregarRol()">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalBorrar">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Borrar Rol Global</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="formulario-borrar">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">

                                            <p>Esta acción eliminara el Rol con todos sus Permisos.</p>

                                            <div class="form-group">
                                                <input type="hidden" id="idborrar">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-danger" onclick="borrar()">Borrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    @stop



    @section('js')
        <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/toastr.min.js') }}"></script>
        <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

        <script>
            $(function () {
                const ruta = "{{ url('/admin/roles/tabla') }}";

                function initDataTable() {
                    // Si ya hay instancia, destrúyela antes de re-crear
                    if ($.fn.DataTable.isDataTable('#tabla')) {
                        $('#tabla').DataTable().destroy();
                    }

                    // Inicializa
                    $('#tabla').DataTable({
                        paging: true,
                        lengthChange: true,
                        searching: true,
                        ordering: true,
                        info: true,
                        autoWidth: false,
                        responsive: true,
                        pagingType: "full_numbers",
                        lengthMenu: [[10, 25, 50, 100, 150, -1],[10, 25, 50, 100, 150, "Todo"]],
                        language: {
                            sProcessing: "Procesando...",
                            sLengthMenu: "Mostrar _MENU_ registros",
                            sZeroRecords: "No se encontraron resultados",
                            sEmptyTable: "Ningún dato disponible en esta tabla",
                            sInfo: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                            sInfoEmpty: "Mostrando 0 a 0 de 0 registros",
                            sInfoFiltered: "(filtrado de _MAX_ registros)",
                            sSearch: "Buscar:",
                            oPaginate: { sFirst: "Primero", sLast: "Último", sNext: "Siguiente", sPrevious: "Anterior" },
                            oAria: { sSortAscending: ": Orden ascendente", sSortDescending: ": Orden descendente" }
                        },
                        dom:
                            "<'row align-items-center'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-md-right'f>>" +
                            "tr" +
                            "<'row align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>"
                    });

                    // Estilitos
                    $('#tabla_length select').addClass('form-control form-control-sm');
                    $('#tabla_filter input').addClass('form-control form-control-sm').css('display','inline-block');
                }

                function cargarTabla() {
                    $('#tablaDatatable').load(ruta, function() {
                        // AQUI debe existir exactamente un <table id="tabla"> en la parcial
                        initDataTable();
                    });
                }

                // Primera carga
                cargarTabla();

                // Exponer recarga para tus flujos (crear/editar)
                window.recargar = function () {
                    cargarTabla();
                };
            });
        </script>


        <script>

            function verInformacion(id){
                window.location.href="{{ url('/admin/roles/lista/permisos') }}/"+id;
            }

            // ver todos los permisos que existen
            function vistaPermisos(){
                window.location.href="{{ url('/admin/roles/permisos/lista') }}";
            }

            function modalAgregar(){
                document.getElementById("formulario-nuevo").reset();
                $('#modalAgregar').modal('show');
            }

            function modalBorrar(id){
                // se obtiene el id del Rol a eliminar globalmente

                $('#idborrar').val(id);
                $('#modalBorrar').modal('show');
            }

            function borrar(){
                openLoading()
                // se envia el ID del Rol
                var idrol = document.getElementById('idborrar').value;

                var formData = new FormData();
                formData.append('idrol', idrol);

                axios.post('/admin/roles/borrar-global', formData, {
                })
                    .then((response) => {
                        closeLoading()
                        $('#modalBorrar').modal('hide');

                        if(response.data.success === 1){
                            toastr.success('Rol global eliminado');
                            recargar();
                        }else{
                            toastr.error('Error al eliminar');
                        }
                    })
                    .catch((error) => {
                        closeLoading();
                        toastr.error('Error al eliminar');
                    });
            }

            function agregarRol(){
                var nombre = document.getElementById('nombre-nuevo').value;

                if(nombre === ''){
                    toastr.error('Nombre es requerido')
                    return;
                }

                if(nombre.length > 30){
                    toastr.error('Máximo 30 caracteres para Nombre')
                    return;
                }

                openLoading()
                var formData = new FormData();
                formData.append('nombre', nombre);

                axios.post('/admin/permisos/nuevo-rol', formData, {
                })
                    .then((response) => {
                        closeLoading()

                        if (response.data.success === 1) {
                            toastr.error('Rol Repetido', 'Cambiar de nombre');
                        }
                        else if(response.data.success === 2){
                            $('#modalAgregar').modal('hide');
                            recargar();
                        }
                        else {
                            toastr.error('Error al guardar');
                        }
                    })
                    .catch((error) => {
                        closeLoading()
                        toastr.error('Error al guardar');
                    });
            }



        </script>


    @endsection
