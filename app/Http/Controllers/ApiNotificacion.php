<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\BitacorasIncidencias;
use Illuminate\Http\Request;
use App\Jobs\EnviarNotificacion;
use Carbon\Carbon;
use OneSignal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class ApiNotificacion extends Controller
{


    public function enviarNotificacion()
    {
        $tokenUsuario = "b638983a-ad28-480e-b087-2096391f5860";
        $tituloNoti = "Enviado desde Laravel";
        $mensajeNoti = "Siii";

        dispatch(new EnviarNotificacion($tokenUsuario, $tituloNoti, $mensajeNoti));

        return "enviado";
    }


    public function listaTicketPendientes()
    {
        try {
            $arrayTicket = BitacorasIncidencias::where('estado', 0)
                ->orderBy('fecha_registro', 'asc')
                ->get();

            $data = $arrayTicket->map(function ($ticket) {

                // Buscar el administrador y su unidad
                $administrador = Administrador::with('unidad')->find($ticket->id_usuario);

                return [
                    'id'                 => $ticket->id,
                    'id_usuario'         => $ticket->id_usuario,
                    'fecha_registro'     => Carbon::parse($ticket->fecha_registro)
                        ->format('d-m-Y h:i A'),
                    'fecha'              => Carbon::parse($ticket->fecha)
                        ->format('d-m-Y'),
                    'tipo_incidente'     => $ticket->tipo_incidente,
                    'sistema_afectado'   => $ticket->sistema_afectado,
                    'nivel'              => $ticket->nivel,
                    'medida_correctivas' => $ticket->medida_correctivas,
                    'observaciones'      => $ticket->observaciones,
                    'estado'             => $ticket->estado,
                    'fecha_solucionado'  => $ticket->fecha_solucionado,
                    'nombre_unidad'      => $administrador?->unidad?->nombre ?? 'Sin unidad',
                ];
            });

            return response()->json([
                'success' => 1,
                'data'    => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'data'    => []
            ], 200);
        }
    }


}
