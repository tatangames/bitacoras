@extends('adminlte::page')

@section('title', 'Generar Ticket')

@section('content_header')
    <h1>Generar Ticket</h1>
@stop
{{-- Activa plugins que necesitas --}}
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

        <section class="content">
            <div class="container-fluid" style="margin: 16px">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-gray-dark">
                            <div class="card-header">
                                <h3 class="card-title">Incidencias</h3>
                            </div>
                            <form id="form-nuevo">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date"
                                               class="form-control"
                                               id="fecha-nuevo"
                                               value="{{ now()->toDateString() }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Tipo Incidente</label>
                                        <input type="text" maxlength="3000" class="form-control" id="tipo-nuevo"
                                               placeholder="Tipo de Incidente">
                                    </div>




                                </div>

                                <div class="card-footer" style="float: right;">
                                    <button id="btn-guardar" type="button" class="btn btn-primary"
                                            onclick="guardarRegistro()">Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @stop


        @section('js')
            <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
            <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

            <script>

                function guardarRegistro(){

                    var fecha = document.getElementById('fecha-nuevo').value;
                    var tipo = document.getElementById('tipo-nuevo').value;

                    if(fecha === ''){
                        toastr.error('Fecha es requerida');
                        return;
                    }

                    const btnGuardar = document.getElementById('btn-guardar');

                    // Desactivar botón al iniciar
                    btnGuardar.disabled = true;
                    btnGuardar.textContent = 'Guardando...';

                    openLoading();
                    var formData = new FormData();
                    formData.append('fecha', fecha);
                    formData.append('tipo', tipo);

                    axios.post(urlAdmin + '/admin/ticket/registro', formData)
                        .then((response) => {
                            closeLoading();
                            if (response.data.success === 1) {
                                toastr.success('Registrado correctamente');
                                document.getElementById('form-nuevo').reset();
                            } else {
                                toastr.error('Error al registrar');
                            }
                        })
                        .catch((error) => {
                            toastr.error('Error al registrar');
                            closeLoading();
                        })
                        .finally(() => {
                            // Reactivar botón al finalizar
                            resetButton();
                        });

                    // Función interna para restaurar el botón
                    function resetButton() {
                        btnGuardar.disabled = false;
                        btnGuardar.textContent = 'Guardar';
                    }
                }

            </script>



@endsection
