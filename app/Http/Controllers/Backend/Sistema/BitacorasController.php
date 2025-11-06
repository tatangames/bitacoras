<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\BitacorasAcceso;
use App\Models\BitacorasIncidencias;
use App\Models\BitacorasMantenimiento;
use App\Models\BitacorasSoporte;
use App\Models\Operador;
use App\Models\TipoAcceso;
use App\Models\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BitacorasController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function registroNovedadesAcceso()
    {
        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();
        $arrayTipoAcceso = TipoAcceso::orderBy('id', 'ASC')->get();

        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d\TH:i');

        return view('backend.admin.novedadesyacceso.vistaregistronovedadesyacceso', compact('arrayOperador', 'fechaHora',
        'arrayTipoAcceso'));
    }

    public function guardarNovedadesAcceso(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
            'operador' => 'required',
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
            $dato->id_operador = $request->operador;
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


    // ============ BITACORA DE NOVEDADES Y ACCESO ===================
    public function indexBitacoraNovedadesAcceso()
    {
        return view('backend.admin.novedadesyacceso.todos.vistanovedadesyacceso');
    }

    public function tablaBitacoraNovedadesAcceso()
    {
        $arrayBitacoraNovedadesAcceso = BitacorasAcceso::orderBy('fecha', 'ASC')->get()
        ->map(function ($item) {

            // Crear campo formateado
            $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y h:i A');

            $infoOperador = Operador::where('id', $item->id_operador)->first();

            $item->nombreOperador = $infoOperador->nombre;
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

        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();
        $arrayTipoAcceso = TipoAcceso::orderBy('id', 'ASC')->get();

        return ['success' => 1, 'info' => $info, 'arrayOperador' => $arrayOperador, 'arrayTipoAcceso' => $arrayTipoAcceso];
    }


    public function actualizarNovedadesAcceso(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'operador' => 'required',
            'acceso' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasAcceso::where('id', $request->id)->update([
                'id_operador' => $request->operador,
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
        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();
        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d');

        return view('backend.admin.mantenimiento.vistaregistromantenimiento', compact('arrayOperador', 'fechaHora'));
    }

    public function guardarMantenimiento(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
            'operador' => 'required',
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
            $dato->id_operador = $request->operador;
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
        return view('backend.admin.mantenimiento.todos.vistamantenimiento');
    }

    public function tablaBitacoraMantenimiento()
    {
        $arrayBitacoraMantenimiento = BitacorasMantenimiento::orderBy('fecha', 'ASC')->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y');

                $fechaProximo = "";
                if($item->proximo_mantenimiento != null){
                    $fechaProximo = Carbon::parse($item->proximo_mantenimiento)->format('d/m/Y');
                }
                $item->fechaProximo = $fechaProximo;

                $infoOperador = Operador::where('id', $item->id_operador)->first();

                $item->nombreOperador = $infoOperador->nombre;

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

        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();

        return ['success' => 1, 'info' => $info, 'arrayOperador' => $arrayOperador];
    }


    public function actualizarMantenimiento(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'operador' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasMantenimiento::where('id', $request->id)->update([
                'id_operador' => $request->operador,
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






    // ============ BITACORA DE SOPORTE  ===================
    public function registroBitacoraSoporte()
    {
        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();
        $arrayUnidad = Unidad::orderBy('nombre', 'ASC')->get();
        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d');

        return view('backend.admin.soporte.vistaregistrosoporte', compact('arrayOperador', 'fechaHora', 'arrayUnidad'));
    }

    public function guardarSoporte(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
            'operador' => 'required',
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

            $dato = new BitacorasSoporte();
            $dato->id_operador = $request->operador;
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
        return view('backend.admin.soporte.todos.vistasoporte');
    }

    public function tablaBitacoraSoporte()
    {
        $arrayBitacoraSoporte = BitacorasSoporte::orderBy('fecha', 'ASC')->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y');


                $infoOperador = Operador::where('id', $item->id_operador)->first();
                $infoUnidad = Unidad::where('id', $item->id_unidad)->first();

                $item->nombreOperador = $infoOperador->nombre;
                $item->nombreUnidad = $infoUnidad->nombre;

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

        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();
        $arrayUnidad = Unidad::orderBy('nombre', 'ASC')->get();

        return ['success' => 1, 'info' => $info, 'arrayOperador' => $arrayOperador,
            'arrayUnidad' => $arrayUnidad];
    }


    public function actualizarSoporte(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'operador' => 'required',
            'unidad' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasSoporte::where('id', $request->id)->update([
                'id_operador' => $request->operador,
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




    // =============================== INCIDENCIAS ========================================

    public function registroBitacoraIncidencias()
    {
        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();
        $fechaHora = Carbon::now('America/El_Salvador')->format('Y-m-d');

        return view('backend.admin.incidencias.vistaregistroincidencias', compact('arrayOperador', 'fechaHora'));
    }


    public function guardarIncidencias(Request $request)
    {
        $regla = array(
            'fecha' => 'required',
            'operador' => 'required',
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
            $dato->id_operador = $request->operador;
            $dato->id_usuario = $userId;
            $dato->fecha_registro = $fechaActual;
            $dato->fecha = $request->fecha;
            $dato->tipo_incidente = $request->tipo;
            $dato->sistema_afectado = $request->sistema;
            $dato->nivel = $request->nivel; // criticos, relevantes, ordinarios
            $dato->medida_correctivas = $request->medida;
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


    //****************


    public function indexBitacoraIncidencias()
    {
        return view('backend.admin.incidencias.todos.vistaincidencias');
    }

    public function tablaBitacoraIncidencias()
    {
        $arrayBitacoraIncidencias = BitacorasIncidencias::orderBy('fecha', 'ASC')->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaFormat = Carbon::parse($item->fecha)->format('d/m/Y');

                $fechaProximo = "";
                if($item->proximo_mantenimiento != null){
                    $fechaProximo = Carbon::parse($item->proximo_mantenimiento)->format('d/m/Y');
                }
                $item->fechaProximo = $fechaProximo;

                $infoOperador = Operador::where('id', $item->id_operador)->first();

                $item->nombreOperador = $infoOperador->nombre;


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

        $arrayOperador = Operador::orderBy('nombre', 'ASC')->get();

        return ['success' => 1, 'info' => $info, 'arrayOperador' => $arrayOperador];
    }


    public function actualizarIncidencias(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'fecha' => 'required',
            'operador' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            BitacorasIncidencias::where('id', $request->id)->update([
                'id_operador' => $request->operador,
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


    public function borrarIncidencias(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }

        BitacorasIncidencias::where('id', $request->id)->delete();

        return ['success' => 1];
    }





}
