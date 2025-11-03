<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\BitacorasAcceso;
use App\Models\BitacorasMantenimiento;
use App\Models\Operador;
use App\Models\TipoAcceso;
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





















}
