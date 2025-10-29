<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\BitacorasAcceso;
use App\Models\Operador;
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

        return view('backend.admin.novedadesyacceso.vistaregistronovedadesyacceso', compact('arrayOperador'));
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
            $dato->tipo_acceso = $request->acceso;
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

            return $item;
        });


        return view('backend.admin.novedadesyacceso.todos.tablanovedadesyacceso', compact('arrayBitacoraNovedadesAcceso'));
    }








}
