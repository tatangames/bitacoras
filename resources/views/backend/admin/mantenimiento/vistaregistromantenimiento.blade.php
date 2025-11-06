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
                            <h3 class="card-title">Mantenimiento</h3>
                        </div>
                        <form id="form-nuevo">
                            <div class="card-body">

                                <div class="form-group" style="width: 30%">
                                    <label>Fecha <span style="color: red">*</span></label>
                                    <input type="date" class="form-control" id="fechahora-nuevo" value="{{ $fechaHora }}">
                                </div>

                                <div class="form-group">
                                    <label>Equipo</label>
                                    <input type="text" class="form-control" id="equipo-nuevo" placeholder="Equipo involucrado">
                                </div>

                                <div class="form-group">
                                    <label>Tipo Mantenimiento:</label>
                                    <br>
                                    <select width="100%" class="form-control" id="select-mantenimiento">
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
                                    <label>Proximo Mantenimiento</label>
                                    <input type="date" class="form-control" id="proximo-nuevo" value="">
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

                var equipo = document.getElementById('equipo-nuevo').value;
                var mantenimiento = document.getElementById('select-mantenimiento').value;
                var descripcion = document.getElementById('descripcion-nuevo').value;
                var proximo = document.getElementById('proximo-nuevo').value;
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
                formData.append('equipo', equipo);
                formData.append('mantenimiento', mantenimiento);
                formData.append('descripcion', descripcion);
                formData.append('proximo', proximo);
                formData.append('observacion', observacion);

                axios.post(url + '/bitacora-mantenimiento/registro', formData)
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
