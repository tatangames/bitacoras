<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\BitacorasAcceso;
use App\Models\BitacorasIncidencias;
use App\Models\BitacorasMantenimiento;
use App\Models\BitacorasSoporte;
use App\Models\TipoAcceso;
use App\Models\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BitacorasController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    private function getTemaPredeterminado(){
        return Auth::guard('admin')->user()->tema;
    }

    public function registroNovedadesAcceso()
    {
        $arrayTipoAcceso = TipoAcceso::orderBy('id', 'ASC')->get();
        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d\TH:i');
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.novedadesyacceso.vistaregistronovedadesyacceso', compact( 'fechaHora',
            'arrayTipoAcceso', 'temaPredeterminado'));
    }

    public function guardarNovedadesAcceso(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
            'acceso' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            $userId  = auth()->id();
            $fechaActual = Carbon::now('America/El_Salvador');

            $dato = new BitacorasAcceso();
            $dato->id_usuario = $userId;
            $dato->fecha = $request->fecha;
            $dato->fecha_registro = $fechaActual;
            $dato->id_acceso = $request->acceso;
            $dato->novedad = $request->novedades;
            $dato->equipo_involucrado = $request->equipo;
            $dato->observaciones = $request->observacion;
            $dato->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }


    // --------------------------------------------------------------------------------------------

    // ============ BITACORA DE NOVEDADES Y ACCESO ===================
    public function indexBitacoraNovedadesAcceso()
    {
        $idusuario = Auth::id();
        $infoUsuario = Administrador::where('id', $idusuario)->first();
        $temaPredeterminado =  $this->getTemaPredeterminado();
        return view('backend.admin.novedadesyacceso.todos.vistanovedadesyacceso', compact('infoUsuario', 'temaPredeterminado'));
    }

    public function tablaBitacoraNovedadesAcceso()
    {

        $idusuario = Auth::id();

        $arrayBitacoraNovedadesAcceso = BitacorasAcceso::where('id_usuario', $idusuario)
            ->orderBy('fecha', 'ASC')->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y h:i A');

                if($item->tipo_acceso == '1'){
                    $item->nombreAcceso = "Salida";
                }else{
                    $item->nombreAcceso = "Entrada";
                }

                return $item;
            });

        return view('backend.admin.novedadesyacceso.todos.tablanovedadesyacceso', compact('arrayBitacoraNovedadesAcceso'));
    }


    public function informacionNovedadesAcceso(Request $request)
    {
        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }

        $info = BitacorasAcceso::where('id', $request->id)->first();

        $arrayTipoAcceso = TipoAcceso::orderBy('id', 'ASC')->get();

        return ['success' => 1, 'info' => $info, 'arrayTipoAcceso' => $arrayTipoAcceso];
    }


    public function actualizarNovedadesAcceso(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'acceso' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasAcceso::where('id', $request->id)->update([
                'fecha' => $request->fecha,
                'id_acceso' => $request->acceso,
                'novedad' => $request->novedades,
                'equipo_involucrado' => $request->equipo,
                'observaciones' => $request->observacion
            ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




    // ============ BITACORA DE MANTENIMIENTO  ===================
    public function registroBitacoraMantenimiento()
    {
        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d');
        $temaPredeterminado =  $this->getTemaPredeterminado();
        return view('backend.admin.mantenimiento.vistaregistromantenimiento', compact( 'fechaHora', 'temaPredeterminado'));
    }

    public function guardarMantenimiento(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            $userId  = auth()->id();
            $fechaActual = Carbon::now('America/El_Salvador');

            $dato = new BitacorasMantenimiento();
            $dato->id_usuario = $userId;
            $dato->fecha = $request->fecha;
            $dato->fecha_registro = $fechaActual;
            $dato->equipo = $request->equipo;
            $dato->tipo_mantenimiento = $request->mantenimiento;
            $dato->descripcion = $request->descripcion;
            $dato->proximo_mantenimiento = $request->proximo;
            $dato->observaciones = $request->observacion;
            $dato->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




    public function indexBitacoraMantenimiento()
    {
        $idusuario = Auth::id();
        $infoUsuario = Administrador::where('id', $idusuario)->first();
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.mantenimiento.todos.vistamantenimiento', compact('infoUsuario', 'temaPredeterminado'));
    }

    public function tablaBitacoraMantenimiento()
    {
        $idusuario = Auth::id();

        $arrayBitacoraMantenimiento = BitacorasMantenimiento::where('id_usuario', $idusuario)
            ->orderBy('fecha', 'ASC')->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y');

                $fechaProximo = "";
                if($item->proximo_mantenimiento != null){
                    $fechaProximo = Carbon::parse($item->proximo_mantenimiento)->format('d/m/Y');
                }
                $item->fechaProximo = $fechaProximo;


                if($item->tipo_mantenimiento == '1'){
                    $item->estadoFormat = "Actualización";
                }
                else if($item->tipo_mantenimiento == '2'){
                    $item->estadoFormat = "Preventivo";
                }
                else{
                    $item->estadoFormat = "Correctivo";
                }

                return $item;
            });

        return view('backend.admin.mantenimiento.todos.tablamantenimiento', compact('arrayBitacoraMantenimiento'));
    }


    public function informacionMantenimiento(Request $request)
    {
        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }

        $info = BitacorasMantenimiento::where('id', $request->id)->first();


        return ['success' => 1, 'info' => $info];
    }


    public function actualizarMantenimiento(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasMantenimiento::where('id', $request->id)->update([
                'fecha' => $request->fecha,
                'equipo' => $request->equipo,
                'tipo_mantenimiento' => $request->mantenimiento,
                'descripcion' => $request->descripcion,
                'proximo_mantenimiento' => $request->proximo,
                'observaciones' => $request->observacion
            ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




// =============================== INCIDENCIAS ========================================

    public function registroBitacoraIncidencias()
    {
        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d');
        $temaPredeterminado =  $this->getTemaPredeterminado();
        return view('backend.admin.incidencias.vistaregistroincidencias', compact('fechaHora', 'temaPredeterminado'));
    }


    public function guardarIncidencias(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
        );


        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            $userId  = auth()->id();
            $fechaActual = Carbon::now('America/El_Salvador');

            $dato = new BitacorasIncidencias();
            $dato->id_usuario = $userId;
            $dato->fecha_registro = $fechaActual;
            $dato->fecha = $request->fecha;
            $dato->tipo_incidente = $request->tipo;
            $dato->sistema_afectado = $request->sistema;
            $dato->nivel = $request->nivel; // criticos, relevantes, ordinarios
            $dato->medida_correctivas = $request->medida;
            $dato->observaciones = $request->observacion;
            $dato->estado = 1; // REGISTRADO MANUAL
            $dato->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }


    //****************


    public function indexBitacoraIncidencias()
    {
        $idusuario = Auth::id();
        $infoUsuario = Administrador::where('id', $idusuario)->first();
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.incidencias.todos.vistaincidencias', compact('infoUsuario', 'temaPredeterminado'));
    }

    public function tablaBitacoraIncidencias()
    {
        $idusuario = Auth::id();

        $arrayBitacoraIncidencias = BitacorasIncidencias::where('id_usuario', $idusuario)
            ->orderBy('fecha', 'ASC')->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y');

                $fechaProximo = "";
                if($item->proximo_mantenimiento != null){
                    $fechaProximo = Carbon::parse($item->proximo_mantenimiento)->format('d/m/Y');
                }
                $item->fechaProximo = $fechaProximo;

                $niveles = [
                    1 => 'Ordinarios',
                    2 => 'Relevantes',
                    3 => 'Críticos',
                ];

                $item->nivelFormat = $niveles[$item->nivel] ?? '';

                return $item;
            });

        return view('backend.admin.incidencias.todos.tablaincidencias', compact('arrayBitacoraIncidencias'));
    }


    public function informacionIncidencias(Request $request)
    {
        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }

        $info = BitacorasIncidencias::where('id', $request->id)->first();


        return ['success' => 1, 'info' => $info];
    }


    public function actualizarIncidencias(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasIncidencias::where('id', $request->id)->update([
                'fecha' => $request->fecha,
                'tipo_incidente' => $request->tipo,
                'sistema_afectado' => $request->sistema,
                'nivel' => $request->nivel,
                'medida_correctivas' => $request->medida,
                'observaciones' => $request->observacion
            ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




// ============ BITACORA DE SOPORTE  ===================
    public function registroBitacoraSoporte()
    {
        $arrayUnidad = Unidad::orderBy('nombre', 'ASC')->get();
        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d');
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.soporte.vistaregistrosoporte', compact( 'fechaHora', 'arrayUnidad', 'temaPredeterminado'));
    }

    public function guardarSoporte(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
            'unidad' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {
            $userId  = auth()->id();
            $fechaActual = Carbon::now('America/El_Salvador');

            $dato = new BitacorasSoporte;
            $dato->id_unidad = $request->unidad;
            $dato->id_usuario = $userId;
            $dato->fecha = $request->fecha;
            $dato->fecha_registro = $fechaActual;
            $dato->solucion = $request->solucion;
            $dato->estado = $request->estado;
            $dato->descripcion = $request->descripcion;
            $dato->observaciones = $request->observacion;
            $dato->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }



    public function indexBitacoraSoporte()
    {
        $idusuario = Auth::id();
        $infoUsuario = Administrador::where('id', $idusuario)->first();
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.soporte.todos.vistasoporte', compact('infoUsuario', 'temaPredeterminado'));
    }

    public function tablaBitacoraSoporte()
    {
        $idusuario = Auth::id();

        $arrayBitacoraSoporte = BitacorasSoporte::where('id_usuario', $idusuario)
            ->orderBy('fecha', 'ASC')->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y');

                $infoUnidad = Unidad::where('id', $item->id_unidad)->first();
                $item->nombreUnidad = $infoUnidad->nombre;

                if($item->estado == '1'){
                    $item->estadoFormat = "Pendiente";
                }else{
                    $item->estadoFormat = "Solucionado";
                }

                return $item;
            });

        return view('backend.admin.soporte.todos.tablasoporte', compact('arrayBitacoraSoporte'));
    }


    public function informacionSoporte(Request $request)
    {
        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }

        $info = BitacorasSoporte::where('id', $request->id)->first();

        $arrayUnidad = Unidad::orderBy('nombre', 'ASC')->get();

        return ['success' => 1, 'info' => $info, 'arrayUnidad' => $arrayUnidad];
    }


    public function actualizarSoporte(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'unidad' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasSoporte::where('id', $request->id)->update([
                'id_unidad' => $request->unidad,
                'fecha' => $request->fecha,
                'descripcion' => $request->descripcion,
                'solucion' => $request->solucion,
                'estado' => $request->estado,
                'observaciones' => $request->observacion
            ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }



    //***************************************************************
    public function vistaReportesTodos()
    {
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.reportes.vistareportetodos', compact('temaPredeterminado'));
    }


    public function generarPdf(Request $request)
    {

        //1- bitacoras acceso
        //2- bitacoras mantenimiento
        //3- bitacoras soporte
        //4- bitacoras incidencias

        $tipo  = $request->tipo;
        $desde = $request->desde;
        $hasta = $request->hasta;
        $desdeFormateado = $desde ? date('d/m/Y', strtotime($desde)) : '';
        $hastaFormateado = $hasta ? date('d/m/Y', strtotime($hasta)) : '';




        switch ($tipo) {

            // =============================
            // 1 - BITÁCORAS DE ACCESO
            // =============================
            case 1:
                $registros = DB::table('bitacoras_acceso as b')
                    ->join('administrador as a', 'a.id', '=', 'b.id_usuario')
                    ->join('tipo_acceso as t', 't.id', '=', 'b.id_acceso')
                    ->whereBetween('b.fecha', [$desde, $hasta])
                    ->select(
                        'b.fecha',
                        'a.nombre as usuario',
                        't.nombre as tipo_acceso',
                        'b.novedad',
                        'b.equipo_involucrado',
                        'b.observaciones'
                    )
                    ->orderBy('b.fecha')
                    ->get();
                break;

            // =============================
            // 2 - MANTENIMIENTO
            // =============================
            case 2:
                $registros = DB::table('bitacoras_mantenimiento as b')
                    ->join('administrador as a', 'a.id', '=', 'b.id_usuario')
                    ->whereBetween('b.fecha', [$desde, $hasta])
                    ->select(
                        'b.fecha',
                        'a.nombre as usuario',
                        'b.equipo',
                        'b.tipo_mantenimiento',
                        'b.descripcion',
                        'b.proximo_mantenimiento',
                        'b.observaciones'
                    )
                    ->orderBy('b.fecha')
                    ->get();
                break;

            // =============================
            // 3 - SOPORTE
            // =============================
            case 3:
                $registros = DB::table('bitacoras_soporte as b')
                    ->join('administrador as a', 'a.id', '=', 'b.id_usuario')
                    ->join('unidad as u', 'u.id', '=', 'b.id_unidad')
                    ->whereBetween('b.fecha', [$desde, $hasta])
                    ->select(
                        'b.fecha',
                        'a.nombre as usuario',
                        'u.nombre as unidad',
                        'b.descripcion',
                        'b.solucion',
                        'b.estado',
                        'b.observaciones'
                    )
                    ->orderBy('b.fecha')
                    ->get();
                break;

            // =============================
            // 4 - INCIDENCIAS
            // =============================
            case 4:
                $registros = DB::table('bitacoras_incidencias as b')
                    ->join('administrador as a', 'a.id', '=', 'b.id_usuario')
                    ->whereBetween('b.fecha', [$desde, $hasta])
                    ->select(
                        'b.fecha',
                        'a.nombre as usuario',
                        'b.tipo_incidente',
                        'b.sistema_afectado',
                        'b.nivel',
                        'b.medida_correctivas',
                        'b.observaciones'
                    )
                    ->orderBy('b.fecha')
                    ->get();
                break;

            default:
                $registros = collect();
        }

        $nombreEncabezado = match ((int)$tipo) {
            1 => 'Bitácora de Acceso y Novedades',
            2 => 'Bitácora de Mantenimiento de Servidores y Sistemas',
            3 => 'Bitácora de Soporte Técnico',
            4 => 'Registro de Incidencias de Ciber Seguridad',
            default => ''
        };

        $nombreCodigo = match ((int)$tipo) {
            1 => 'TECN-001-BITA',
            2 => 'TECN-003-BITA',
            3 => 'TECN-002-BITA',
            4 => 'TECT-004-BITA',
            default => ''
        };


        $nombreTitulo = match ((int)$tipo) {
            1 => 'UNIDAD DE TECNOLOGÍAS DE LA INFORMACIÓN <br> BITÁCORA DE ACCESO Y NOVEDADES',
            2 => 'UNIDAD DE TECNOLOGÍAS DE LA INFORMACIÓN <br> BITÁCORA DE MANTENIMIENTO DE SERVIDORES Y SISTEMAS',
            3 => 'UNIDAD DE TECNOLOGÍAS DE LA INFORMACIÓN <br> BITÁCORA DE SOPORTE TÉCNICO',
            4 => 'UNIDAD DE TECNOLOGÍAS DE LA INFORMACIÓN <br> BITÁCORA DE INCIDENCIAS DE CIBER SEGURIDAD',
            default => ''
        };





        $fechaGenerado = date('d/m/Y');




        // =========================
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => sys_get_temp_dir(),
            'format' => 'LETTER',
            'orientation' => 'L'
        ]);



        // =========================
        // HTML PDF
        // =========================

        $mpdf->SetTitle('Reporte');

        // mostrar errores
        $mpdf->showImageErrors = false;

        $logoalcaldia = 'images/gobiernologo.jpg';

        $tabla = "
           <table width='100%' style='border-collapse:collapse; font-family: Arial, sans-serif;'>
            <tr>
                <td style='width:25%; border:0.8px solid #000; padding:6px 8px;'>
                    <table width='100%'>
                        <tr>
                            <td style='width:30%; text-align:left;'>
                                <img src='{$logoalcaldia}' style='height:38px'>
                            </td>
                            <td style='width:70%; text-align:left; color:#104e8c; font-size:13px; font-weight:bold; line-height:1.3;'>
                                SANTA ANA NORTE<br>EL SALVADOR
                            </td>
                        </tr>
                    </table>
                </td>
                <td style='width:50%; border-top:0.8px solid #000; border-bottom:0.8px solid #000; padding:6px 8px; text-align:center; font-size:15px; font-weight:bold;'>
                    $nombreEncabezado<br>
                </td>
                <td style='width:25%; border:0.8px solid #000; padding:0; vertical-align:top;'>
                    <table width='100%' style='font-size:10px;'>
                        <tr>
                            <td width='40%' style='border-right:0.8px solid #000; border-bottom:0.8px solid #000; padding:4px 6px;'><strong>Código:</strong></td>
                            <td width='60%' style='border-bottom:0.8px solid #000; padding:4px 6px; text-align:center;'>$nombreCodigo</td>
                        </tr>
                        <tr>
                            <td style='border-right:0.8px solid #000; border-bottom:0.8px solid #000; padding:4px 6px;'><strong>Versión:</strong></td>
                            <td style='border-bottom:0.8px solid #000; padding:4px 6px; text-align:center;'>000</td>
                        </tr>
                        <tr>
                            <td style='border-right:0.8px solid #000; padding:4px 6px;'><strong>Fecha de vigencia:</strong></td>
                            <td style='padding:4px 6px; text-align:center;'>22/10/2025</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>";

        $tabla .= '<p style="text-align:center; font-weight:bold;">' . $nombreTitulo . '</p>';


        $tabla .= "<table width='100%' style='border-collapse:collapse; font-size:10px;' border='1' cellpadding='4'>";

        if($tipo == 1){ // ACCESO

            $tabla .= "
    <tr style='background:#f0f0f0; font-weight:bold;'>
        <td>FECHA</td>
        <td>HORA</td>
        <td>OPERADOR</td>
        <td>TIPO DE ACCESO</td>
        <td>NOVEDAD</td>
        <td>EQUIPO INVOLUCRADO</td>
        <td>OBSERVACIONES</td>
        <td style='width: 10%'>FIRMA</td>
    </tr>";

            foreach($registros as $r){
                $tabla .= "
        <tr>
    <td style='padding:3px;'>".$this->fechaDMY($r->fecha)."</td>
    <td style='padding:3px;'>".$this->fechaHora($r->fecha)."</td>
    <td style='padding:3px;'>{$r->usuario}</td>
    <td style='padding:3px;'>{$r->tipo_acceso}</td>
    <td style='padding:3px;'>{$r->novedad}</td>
    <td style='padding:3px;'>{$r->equipo_involucrado}</td>
    <td style='padding:3px;'>{$r->observaciones}</td>
    <td style='height:30px;'></td>
</tr>";
            }
        }


        if($tipo == 2){ // MANTENIMIENTO

            $tabla .= "
            <tr style='background:#f0f0f0; font-weight:bold;'>
                <td>FECHA</td>
                <td>EQUIPO</td>
                <td>TIPO MTTO</td>
                <td>DESCRIPCIÓN</td>
                <td>TÉCNICO</td>
                <td>PRÓXIMO MTTO</td>
                <td>OBSERVACIONES</td>
            </tr>";

            foreach($registros as $r){
                $tabla .= "
        <tr>
            <td>".$this->fechaDMY($r->fecha)."</td>
            <td>{$r->equipo}</td>
            <td>".$this->estadoMantenimientoTexto($r->tipo_mantenimiento)."</td>
            <td>{$r->descripcion}</td>
            <td>{$r->usuario}</td>
             <td>".$this->fechaDMY($r->proximo_mantenimiento)."</td>
            <td>{$r->observaciones}</td>
        </tr>";
            }
        }


        if($tipo == 3){ // SOPORTE

            $tabla .= "
<tr style='background:#f0f0f0; font-weight:bold;'>
    <td>No.</td>
    <td>FECHA</td>
    <td>UNIDAD</td>
    <td>DESCRIPCIÓN</td>
    <td>TÉCNICO</td>
    <td>SOLUCIÓN</td>
    <td>ESTADO</td>
    <td>OBSERVACIONES</td>
</tr>";

            $i = 1; // contador correlativo

            foreach($registros as $r){
                $tabla .= "
                <tr>
                    <td style='text-align:center;'>".$i."</td>
                    <td>".$this->fechaDMY($r->fecha)."</td>
                    <td>{$r->unidad}</td>
                    <td>{$r->descripcion}</td>
                    <td>{$r->usuario}</td>
                    <td>{$r->solucion}</td>
                    <td>".$this->estadoSoporteTexto($r->estado)."</td>
                    <td>{$r->observaciones}</td>
                </tr>";
                $i++;
            }
        }




        if($tipo == 4){ // INCIDENCIAS

            $tabla .= "
    <tr style='background:#f0f0f0; font-weight:bold;'>
        <td>FECHA</td>
        <td>TIPO DE INCIDENTE</td>
        <td>SISTEMA AFECTADO</td>
        <td>NIVEL</td>
        <td>RESPONSABLE</td>
        <td>MEDIDAS CORRECTIVAS</td>
        <td>OBSERVACIONES</td>
    </tr>";

            foreach($registros as $r){
                $tabla .= "
        <tr>
            <td>".$this->fechaDMY($r->fecha)."</td>
            <td>{$r->tipo_incidente}</td>
            <td>{$r->sistema_afectado}</td>
            <td>".$this->estadoIncidenciasTexto($r->nivel)."</td>
            <td>{$r->usuario}</td>
            <td>{$r->medida_correctivas}</td>
            <td>{$r->observaciones}</td>
        </tr>";
            }
        }

        $mpdf->SetFooter('Página {PAGENO} de {nbpg}');
        $tabla .= "</table>";
        $mpdf->WriteHTML($tabla);
        $mpdf->Output();
    }


    private function fechaDMY($fecha){
        if(!$fecha) return '';
        return date('d-m-Y', strtotime($fecha));
    }

    private function fechaHora($fecha){
        if(!$fecha) return '';
        return date('h:i A', strtotime($fecha));
    }

    private function estadoSoporteTexto($estado)
    {
        return match ((int)$estado) {
            1 => 'Pendiente',
            2 => 'Solucionado',
            default => '—'
        };
    }

    private function estadoMantenimientoTexto($estado)
    {
        return match ((int)$estado) {
            1 => 'Actualización',
            2 => 'Preventivo',
            3 => 'Correctivo',
            default => '—'
        };
    }


    private function estadoIncidenciasTexto($estado)
    {
        return match ((int)$estado) {
            1 => 'Ordinarios',
            2 => 'Relevantes',
            3 => 'Críticos',
            default => '—'
        };
    }







}
