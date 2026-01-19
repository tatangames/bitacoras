@extends('adminlte::page')

@section('title', 'Reportes')

@section('content_header')
    <h1>Reportes</h1>
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
        <a class="nav-link" data-toggle="dropdown" href="#" title="Tema">
            <i id="theme-icon" class="fas fa-sun"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-0" style="min-width: 180px">
            <a class="dropdown-item d-flex align-items-center" href="#" data-theme="dark">
                <i class="far fa-moon mr-2"></i> Dark
            </a>
            <a class="dropdown-item d-flex align-items-center" href="#" data-theme="light">
                <i class="far fa-sun mr-2"></i> Light
            </a>
        </div>
    </li>

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
                                <h3 class="card-title">Reportes</h3>
                            </div>
                            <form id="form-nuevo">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Tipo de Reporte</label>
                                        <select class="form-control" id="select-tipo">
                                            <option value="1">Bitacora de Acceso</option>
                                            <option value="2">Bitacora de Mantenimiento</option>
                                            <option value="3">Bitacora de Soporte</option>
                                            <option value="4">Bitacora de Incidencias</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Desde</label>
                                        <input type="date" class="form-control" id="fecha-desdelote">
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Hasta</label>
                                        <input type="date" class="form-control" id="fecha-hastalote">
                                    </div>
                                </div>

                                <div class="card-footer" style="float: right;">
                                    <button type="button" onclick="pdfGenerar()" class="btn" style="margin-left: 15px; border-color: black; border-radius: 0.1px;">
                                        <img src="{{ asset('images/logopdf.png') }}" width="48px" height="55px">
                                        Generar PDF
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- FORMULARIO OCULTO PARA PDF -->
    <form id="formPdfReporte" method="POST" target="_blank"
          action="{{ route('admin.generar.reporte.pdf') }}">
        @csrf
        <input type="hidden" name="tipo" id="pdf_tipo">
        <input type="hidden" name="desde" id="pdf_desde">
        <input type="hidden" name="hasta" id="pdf_hasta">
    </form>


        @stop

        @section('js')
            <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
            <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

            <script>

                function pdfGenerar(){

                    let tipo = $('#select-tipo').val();
                    let desde = $('#fecha-desdelote').val();
                    let hasta = $('#fecha-hastalote').val();

                    if(!desde){
                        toastr.error('Fecha desde es requerida');
                        return;
                    }

                    if(!hasta){
                        toastr.error('Fecha hasta es requerida');
                        return;
                    }

                    if(new Date(hasta) < new Date(desde)){
                        toastr.error('La Fecha Hasta no puede ser menor que la Fecha Desde');
                        return;
                    }

                    // Asignar valores al formulario oculto
                    $('#pdf_tipo').val(tipo);
                    $('#pdf_desde').val(desde);
                    $('#pdf_hasta').val(hasta);

                    // Enviar formulario
                    $('#formPdfReporte').submit();
                }


            </script>

            <script>
                (function () {
                    // ===== Config inicial =====
                    const SERVER_DEFAULT = {{ $temaPredeterminado }}; // 0 = light, 1 = dark
                    const iconEl = document.getElementById('theme-icon');

                    // CSRF para axios
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (token) axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

                    // ===== Funciones =====
                    function applyTheme(mode) {
                        const dark = mode === 'dark';

                        // AdminLTE v3
                        document.body.classList.toggle('dark-mode', dark);

                        // AdminLTE v4
                        document.documentElement.setAttribute('data-bs-theme', dark ? 'dark' : 'light');

                        // Icono
                        if (iconEl) {
                            iconEl.classList.remove('fa-sun', 'fa-moon');
                            iconEl.classList.add(dark ? 'fa-moon' : 'fa-sun');
                        }
                    }

                    function themeToInt(mode) {
                        return mode === 'dark' ? 1 : 0;
                    }

                    function intToTheme(v) {
                        return v === 1 ? 'dark' : 'light';
                    }

                    // ===== Aplicar tema inicial desde servidor =====
                    applyTheme(intToTheme(SERVER_DEFAULT));

                    // ===== Manejo de clicks y POST a backend =====
                    let saving = false;

                    document.addEventListener('click', async (e) => {
                        const a = e.target.closest('.dropdown-item[data-theme]');
                        if (!a) return;
                        e.preventDefault();
                        if (saving) return;

                        const selectedMode = a.dataset.theme; // 'dark' | 'light'
                        const newValue = themeToInt(selectedMode);

                        // Modo optimista: aplicar de una vez
                        const previousMode = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
                        applyTheme(selectedMode);

                        try {
                            saving = true;
                            await axios.post(urlAdmin + '/admin/actualizar/tema', {tema: newValue});
                            // Si querés, mostrar un toast:
                            if (window.toastr) toastr.success('Tema actualizado');
                        } catch (err) {
                            // Revertir si falló
                            applyTheme(previousMode);
                            if (window.toastr) {
                                toastr.error('No se pudo actualizar el tema');
                            } else {
                                alert('No se pudo actualizar el tema');
                            }
                        } finally {
                            saving = false;
                        }
                    });
                })();
            </script>

@endsection






















