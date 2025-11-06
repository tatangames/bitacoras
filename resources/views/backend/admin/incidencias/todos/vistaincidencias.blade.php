@extends('backend.menus.superior')

@section('content-admin-css')
    <link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
    <link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
@stop

<style>
    table{
        /*Ajustar tablas*/
        table-layout:fixed;
    }
</style>

<div id="divcontenedor" style="display: none">

    <section class="content-header">
        <div class="row mb-2">
            <div class="col-sm-6">
                <label>Usuario: {{ $infoUsuario->nombre }}</label>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">Bitacoras</li>
                    <li class="breadcrumb-item active">Listado de Incidencias</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-gray-dark">
                <div class="card-header">
                    <h3 class="card-title">Listado</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="tablaDatatable">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- modal editar -->
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
                                        <label>Operador: <span style="color: red">*</span></label>
                                        <br>
                                        <select width="100%" class="form-control" id="select-operador">
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Tipo Incidente</label>
                                        <input type="text" maxlength="3000" class="form-control" id="tipo-nuevo" placeholder="Tipo de Incidente">
                                    </div>

                                    <div class="form-group">
                                        <label>Sistema Afectado</label>
                                        <input type="text" maxlength="3000" class="form-control" id="sistema-nuevo" placeholder="sistema Afectado">
                                    </div>

                                    <div class="form-group">
                                        <label>Nivel: <span style="color: red">*</span></label>
                                        <br>
                                        <select width="100%" class="form-control" id="select-nivel">
                                            <option value="1">Ordinarios</option>
                                            <option value="2">Relevantes</option>
                                            <option value="3">Críticos</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Medida Correctivas</label>
                                        <input type="text" maxlength="3000" class="form-control" id="medida-nuevo" placeholder="Medidas Correctivas">
                                    </div>

                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <input type="text" maxlength="3000" class="form-control" id="observacion-nuevo" placeholder="Observaciones">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    <button type="button" style="font-weight: bold; background-color: #28a745; color: white !important;" id="btn-guardar" class="button button-rounded button-pill button-small" onclick="editar()">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>


@extends('backend.menus.footerjs')
@section('archivos-js')

    <script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>

    <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            var ruta = "{{ URL::to('/admin/bitacora/lista/incidencias/tabla') }}";
            $('#tablaDatatable').load(ruta);

            document.getElementById("divcontenedor").style.display = "block";

        });
    </script>

    <script>

        function recargar(){
            var ruta = "{{ URL::to('/admin/bitacora/lista/incidencias/tabla') }}";
            $('#tablaDatatable').load(ruta);
        }

        function modalAgregar(){
            document.getElementById("formulario-nuevo").reset();
            $('#modalAgregar').modal('show');
        }

        function informacion(id){
            openLoading();
            document.getElementById("formulario-editar").reset();

            axios.post(url+'/bitacora/incidencias/informacion',{
                'id': id
            })
                .then((response) => {
                    closeLoading();
                    if(response.data.success === 1){
                        $('#modalEditar').modal('show');
                        $('#id-editar').val(id);
                        $('#fechahora-nuevo').val(response.data.info.fecha);


                        $('#tipo-nuevo').val(response.data.info.tipo_incidente);
                        $('#sistema-nuevo').val(response.data.info.sistema_afectado);

                        $('#medida-nuevo').val(response.data.info.medida_correctivas);
                        $('#observacion-nuevo').val(response.data.info.observaciones);

                        document.getElementById("select-operador").options.length = 0;

                        $.each(response.data.arrayOperador, function( key, val ){
                            if(response.data.info.id_operador == val.id){
                                $('#select-operador').append('<option value="' +val.id +'" selected="selected">'+ val.nombre +'</option>');
                            }else{
                                $('#select-operador').append('<option value="' +val.id +'">'+ val.nombre +'</option>');
                            }
                        });

                        document.getElementById('select-nivel').value = response.data.info.nivel;

                    }else{
                        toastr.error('Información no encontrada');
                    }
                })
                .catch((error) => {
                    closeLoading();
                    toastr.error('Información no encontrada');
                });
        }

        function editar(){
            var id = document.getElementById('id-editar').value;
            var fecha = document.getElementById('fechahora-nuevo').value;
            var selectOperador = document.getElementById('select-operador').value;
            var tipo = document.getElementById('tipo-nuevo').value;
            var sistema = document.getElementById('sistema-nuevo').value;
            var nivel = document.getElementById('select-nivel').value;
            var medida = document.getElementById('medida-nuevo').value;
            var observacion = document.getElementById('observacion-nuevo').value;


            if(fecha === ''){
                toastr.error('Fecha es requerida');
                return;
            }

            if(selectOperador === ''){
                toastr.error('Operador es requerida');
                return;
            }

            const btnGuardar = document.getElementById('btn-guardar');

            // Desactivar botón al iniciar
            btnGuardar.disabled = true;
            btnGuardar.textContent = 'Guardando...';

            openLoading();
            var formData = new FormData();
            formData.append('id', id);
            formData.append('fecha', fecha);
            formData.append('operador', selectOperador);
            formData.append('tipo', tipo);
            formData.append('sistema', sistema);
            formData.append('nivel', nivel);
            formData.append('medida', medida);
            formData.append('observacion', observacion);

            axios.post(url + '/bitacora/incidencias/actualizar', formData)
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.success('Actualizado correctamente');
                        $('#modalEditar').modal('hide');
                        recargar()
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


        function modalBorrar(id){
            Swal.fire({
                title: '¿Borrar?',
                text: "",
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Si'
            }).then((result) => {
                if (result.isConfirmed) {
                    borrarFila(id);
                }
            })
        }

        function borrarFila(id){
            openLoading();
            var formData = new FormData();
            formData.append('id', id);

            axios.post(url + '/bitacora/incidencias/borrar', formData)
                .then((response) => {
                    closeLoading();
                    if (response.data.success === 1) {
                        toastr.success('Borrado correctamente');
                        recargar()
                    } else {
                        toastr.error('Error al registrar');
                    }
                })
                .catch((error) => {
                    toastr.error('Error al registrar');
                    closeLoading();
                })
                .finally(() => {

                });
        }



    </script>


@endsection
