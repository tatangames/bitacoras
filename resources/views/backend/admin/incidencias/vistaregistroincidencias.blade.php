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

                                <div class="form-group" style="width: 30%">
                                    <label>Fecha <span style="color: red">*</span></label>
                                    <input type="date" class="form-control" id="fechahora-nuevo" value="{{ $fechaHora }}">
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

                            <div class="card-footer" style="float: right;">
                                <button id="btn-guardar" type="button" class="btn btn-primary" onclick="guardarRegistro()">Guardar</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>


    @extends('backend.menus.footerjs')
    @section('archivos-js')


        <script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                document.getElementById("divcontenedor").style.display = "block";
            });
        </script>


        <script>

            function guardarRegistro(){

                var fecha = document.getElementById('fechahora-nuevo').value;
                var tipo = document.getElementById('tipo-nuevo').value;
                var sistema = document.getElementById('sistema-nuevo').value;
                var nivel = document.getElementById('select-nivel').value;
                var medida = document.getElementById('medida-nuevo').value;
                var observacion = document.getElementById('observacion-nuevo').value;


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
                formData.append('sistema', sistema);
                formData.append('nivel', nivel);
                formData.append('medida', medida);
                formData.append('observacion', observacion);

                axios.post(url + '/bitacora-incidencias/registro', formData)
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
