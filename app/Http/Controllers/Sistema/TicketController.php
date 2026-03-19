<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Jobs\AlertaTicketJob;
use App\Mail\AlertaTicketMail;
use App\Models\Administrador;
use App\Models\BitacorasIncidencias;
use App\Models\Unidad;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Jobs\EnviarNotificacion;
use OneSignal;

class TicketController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }


    public function indexGenerarTicket()
    {

        return view('backend.admin.ticket.generar.indexgenerarticket');
    }




    // ENVIO DE CORREO Y NOTIFICACIONES PUSH EN BACKGROUND PARA ALERTAS


    public function generarTicket(Request $request)
    {
        DB::beginTransaction();

        try {
            $userId = auth()->id();
            $infoUsuario = Administrador::with('unidad')->findOrFail($userId);
            $nombreUnidad = $infoUsuario->unidad?->nombre ?? '';

            $fechaActual = Carbon::now('America/El_Salvador');

            $dato = new BitacorasIncidencias();
            $dato->id_usuario = $userId;
            $dato->fecha_registro = $fechaActual;
            $dato->fecha = $request->fecha;
            $dato->tipo_incidente = $request->tipo;
            $dato->nivel = 1;
            $dato->estado = 0;
            $dato->save();

            DB::commit();

            $tituloNoti = "Ticket  #" . $dato->id;
            $mensajeNoti = $nombreUnidad;

            // Obtener todos los tokens no nulos de la tabla Administrador
            $tokens = Administrador::whereNotNull('onesignal_player_id')
                ->where('onesignal_player_id', '!=', '')
                ->pluck('onesignal_player_id')
                ->toArray();

            if (!empty($tokens)) {
                dispatch(new EnviarNotificacion($tokens, $tituloNoti, $mensajeNoti));
            }

            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }



    public function indexTicketPendiente()
    {
        return view('backend.admin.ticket.pendientes.vistapendienteticket');
    }

    public function tablaTicketPendiente()
    {
        $idusuario = Auth::id();

        $arrayBitacoraIncidencias = BitacorasIncidencias::where('id_usuario', $idusuario)
            ->where('estado', 0)
            ->orderBy('fecha', 'ASC')
            ->get()
            ->map(function ($item) {

                // Crear campo formateado
                $item->fechaRegistrado = Carbon::parse($item->fecha_registro)->format('d/m/Y h:i A');


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

        return view('backend.admin.ticket.pendientes.tablapendienteticket', compact('arrayBitacoraIncidencias'));
    }

    public function solucionarIncidencia(Request $request)
    {
        $regla = array(
            'id' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            $fechaActual = Carbon::now('America/El_Salvador');

            BitacorasIncidencias::where('id', $request->id)->update([
                'fecha_solucionado' => $fechaActual,
            ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }



    public function indexTicketPendientePorRevisar()
    {
        return view('backend.admin.ticket.completados.vistacompletadoticket');
    }


    public function tablaTicketPendientePorRevisar(Request $request)
    {
        $arrayBitacoraIncidencias = BitacorasIncidencias::orderBy('fecha', 'ASC')
            ->whereIn('estado', [0,1]) // PENDIENTE Y EN PROCESO
            ->get()
            ->map(function ($item) {

                $infoAdmin = Administrador::where('id', $item->id_usuario)->first();

                $nombreUnidad = "";
                if($infoUnidad = Unidad::where('id', $infoAdmin->id_unidad)->first()){
                    $nombreUnidad = $infoUnidad->nombre;
                }
                $item->nombreUnidad = $nombreUnidad;

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

        return view('backend.admin.ticket.completados.tablacompletadoticket', compact('arrayBitacoraIncidencias'));
    }


    public function revisadoTicketCompletado(Request $request)
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
                'observaciones' => $request->observacion,
            ]);

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }


    public function completarRevisionTicket(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'estado' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {

            $idusuario = Auth::id();

            if($request->estado == 1){ // EN PROCESO
                BitacorasIncidencias::where('id', $request->id)->update([
                    'fecha_enproceso' => Carbon::now('America/El_Salvador'),
                    'id_usuario_enproceso' => $idusuario,
                ]);
            }

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }






}
