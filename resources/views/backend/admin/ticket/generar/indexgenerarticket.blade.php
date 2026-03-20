@extends('adminlte::page')

@section('title', 'Generar Ticket')

@section('content_header')
    <h1>Generar Ticket</h1>
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

    {{-- ── Estilos del campo opcional ───────────────────────────────────── --}}
    <style>
        .label-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
        }
        .badge-optional {
            font-size: 0.68rem;
            font-weight: 600;
            color: #6b7280;
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            padding: 1px 9px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .upload-zone {
            position: relative;
            border: 2px dashed #adb5bd;
            border-radius: 8px;
            padding: 1.2rem 1rem;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: border-color 0.2s, background 0.2s;
        }
        .upload-zone:hover {
            border-color: #6c757d;
            background: #f1f3f5;
        }
        .upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }
        .upload-icon  { font-size: 1.6rem; display: block; margin-bottom: 4px; }
        .upload-title { font-size: 0.875rem; font-weight: 600; color: #374151; }
        .upload-hint  { font-size: 0.775rem; color: #9ca3af; margin-top: 2px; }

        .preview-box {
            display: none;
            align-items: center;
            gap: 10px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 7px 12px;
            margin-top: 8px;
        }
        .preview-box.visible { display: flex; }
        .preview-name {
            font-size: 0.82rem;
            color: #166534;
            font-weight: 500;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .preview-remove {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            color: #6b7280;
            line-height: 1;
            padding: 0;
        }
        .preview-remove:hover { color: #ef4444; }
        .field-hint { font-size: 0.775rem; color: #9ca3af; margin-top: 5px; }
    </style>

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

                                    {{-- Fecha --}}
                                    <div class="form-group">
                                        <label>Fecha</label>
                                        <input type="date"
                                               class="form-control"
                                               id="fecha-nuevo"
                                               value="{{ now()->toDateString() }}">
                                    </div>

                                    {{-- Tipo de Incidente --}}
                                    <div class="form-group">
                                        <label>Tipo Incidente</label>
                                        <input type="text"
                                               maxlength="3000"
                                               class="form-control"
                                               id="tipo-nuevo"
                                               placeholder="Tipo de Incidente">
                                    </div>

                                    <hr>

                                    {{-- Documento / Captura (OPCIONAL) --}}
                                    <div class="form-group">
                                        <div class="label-row">
                                            <label for="documento-control">Captura de pantalla / Documento</label>
                                            <span class="badge-optional">Opcional</span>
                                        </div>

                                        <div class="upload-zone" id="upload-zone">
                                            <input type="file"
                                                   id="documento-control"
                                                   accept="image/jpeg, image/jpg, image/png, .pdf"
                                                   onchange="handleFile(this)"/>
                                            <span class="upload-icon">🖼️</span>
                                            <div class="upload-title">Arrastra o haz clic para subir</div>
                                            <div class="upload-hint">JPG, PNG o PDF · máx. 5 MB</div>
                                        </div>

                                        {{-- Preview del archivo seleccionado --}}
                                        <div class="preview-box" id="preview-box">
                                            <span>📎</span>
                                            <span class="preview-name" id="preview-name"></span>
                                            <button type="button"
                                                    class="preview-remove"
                                                    onclick="removeFile()"
                                                    title="Quitar archivo">✕</button>
                                        </div>

                                        <p class="field-hint">ℹ️ Si tienes una captura del código AnyDesk, adjúntala aquí.</p>
                                    </div>

                                </div>{{-- /card-body --}}

                                <div class="card-footer" style="float: right;">
                                    <button id="btn-guardar"
                                            type="button"
                                            class="btn btn-primary"
                                            onclick="guardarRegistro()">
                                        Guardar
                                    </button>
                                </div>
                            </form>

                        </div>{{-- /card --}}
                    </div>
                </div>
            </div>
        </section>
    </div>

@stop

@section('js')
    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}" type="text/javascript"></script>

    <script>

        // ── Preview del archivo ──────────────────────────────────────────
        function handleFile(input) {
            const file = input.files[0];
            if (!file) return;
            document.getElementById('preview-name').textContent = file.name;
            document.getElementById('preview-box').classList.add('visible');
            document.getElementById('upload-zone').style.borderColor = '#6366f1';
        }

        function removeFile() {
            document.getElementById('documento-control').value = '';
            document.getElementById('preview-box').classList.remove('visible');
            document.getElementById('upload-zone').style.borderColor = '';
        }

        // ── Guardar registro ─────────────────────────────────────────────
        function guardarRegistro() {
            const fecha = document.getElementById('fecha-nuevo').value.trim();
            const tipo  = document.getElementById('tipo-nuevo').value.trim();

            // Validaciones obligatorias
            if (!fecha) { toastr.error('La fecha es requerida');             return; }
            if (!tipo)  { toastr.error('El tipo de incidente es requerido'); return; }

            // Validación opcional del archivo (solo si se seleccionó uno)
            const documentoInput = document.getElementById('documento-control');
            if (documentoInput.files.length > 0) {
                const file         = documentoInput.files[0];
                const allowed      = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                const maxSizeBytes = 5 * 1024 * 1024; // 5 MB

                if (!allowed.includes(file.type)) {
                    toastr.error('Formatos permitidos: .png .jpg .jpeg .pdf');
                    return;
                }
                if (file.size > maxSizeBytes) {
                    toastr.error('El archivo no debe superar los 5 MB');
                    return;
                }
            }

            // Deshabilitar botón mientras guarda
            const btnGuardar       = document.getElementById('btn-guardar');
            btnGuardar.disabled    = true;
            btnGuardar.textContent = 'Guardando...';
            openLoading();

            // FormData — documento solo se agrega si existe
            const formData = new FormData();
            formData.append('fecha', fecha);
            formData.append('tipo',  tipo);
            if (documentoInput.files.length > 0) {
                formData.append('documento', documentoInput.files[0]);
            }

            axios.post(urlAdmin + '/admin/ticket/registro', formData)
                .then(({ data }) => {
                    if (data.success === 1) {
                        toastr.success('Registrado correctamente');
                        document.getElementById('form-nuevo').reset();
                        removeFile(); // limpiar preview tras guardar
                    } else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch(() => toastr.error('Error al registrar'))
                .finally(() => {
                    closeLoading();
                    btnGuardar.disabled    = false;
                    btnGuardar.textContent = 'Guardar';
                });
        }

    </script>
@endsection
