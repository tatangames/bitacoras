@extends('adminlte::page')

@section('title', 'Soporte')

@section('content_header')
    <h1>Soporte</h1>
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
                                <h3 class="card-title">Soporte</h3>
                            </div>
                            <form id="form-nuevo">
                                <div class="card-body">

                                    <div class="form-group" style="width: 75%">
                                        <label>Fecha <span style="color: red">*</span></label>
                                        <input type="date" class="form-control" id="fechahora-nuevo" value="{{ $fechaHora }}">
                                    </div>

                                    <div class="form-group">
                                        <label>Unidad: <span style="color: red">*</span></label>
                                        <br>
                                        <select width="100%" class="form-control" id="select-unidad">
                                            @foreach($arrayUnidad as $item)
                                                <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Descripción</label>
                                        <input type="text" class="form-control" id="descripcion-nuevo" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label>Solución</label>
                                        <input type="text" class="form-control" id="solucion-nuevo" placeholder="">
                                    </div>

                                    <div class="form-group">
                                        <label>Estado:</label>
                                        <br>
                                        <select width="100%" class="form-control" id="select-estado">
                                            <option value="1">Pendiente</option>
                                            <option value="2">Solucionado</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <input type="text" class="form-control" id="observacion-nuevo" placeholder="Observaciones">
                                    </div>

                                </div>

                                <div class="card-footer" style="float: right;">
                                    <button id="btn-guardar" type="button" class="btn btn-primary" onclick="guardarRegistro()">Guardar</button>
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

        $(document).ready(function () {
            // Inicializar Select2 con tema Bootstrap 5
            $('#select-unidad').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                width: '100%'
            });
        });

        function guardarRegistro(){

            var fecha = document.getElementById('fechahora-nuevo').value;
            var selectUnidad = document.getElementById('select-unidad').value;

            var descripcion = document.getElementById('descripcion-nuevo').value;
            var solucion = document.getElementById('solucion-nuevo').value;
            var estado = document.getElementById('select-estado').value;
            var observacion = document.getElementById('observacion-nuevo').value;


            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }

            if(selectUnidad === ''){
                toastr.error('Unidad es requerida');
                return;
            }

            const btnGuardar = document.getElementById('btn-guardar');

            // Desactivar botón al iniciar
            btnGuardar.disabled = true;
            btnGuardar.textContent = 'Guardando...';

            openLoading();
            var formData = new FormData();
            formData.append('fecha', fecha);
            formData.append('unidad', selectUnidad);
            formData.append('descripcion', descripcion);
            formData.append('solucion', solucion);
            formData.append('estado', estado);
            formData.append('observacion', observacion);

            axios.post(urlAdmin + '/admin/bitacora-soporte/registro', formData)
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.success('Registrado correctamente');
                        document.getElementById('form-nuevo').reset();
                        const sel = document.getElementById("select-unidad");
                        sel.selectedIndex = 0;
                        $('#select-unidad').val("").trigger("change");
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
