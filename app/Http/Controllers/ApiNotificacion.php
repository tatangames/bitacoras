<?php

namespace App\Http\Controllers;

use App\Models\Administrador;
use App\Models\BitacorasIncidencias;
use App\Jobs\EnviarNotificacion;
use Carbon\Carbon;
use OneSignal;

class ApiNotificacion extends Controller
{


    public function enviarNotificacion()
    {

        $tituloNoti = "Enviado desde Laravel";
        $mensajeNoti = "Siii";

        // Obtener todos los tokens no nulos de la tabla Administrador
        $tokens = Administrador::whereNotNull('onesignal_player_id')
            ->where('onesignal_player_id', '!=', '')
            ->pluck('onesignal_player_id')
            ->toArray();

        if (empty($tokens)) {
            return "No hay administradores con token registrado";
        }

        dispatch(new EnviarNotificacion($tokens, $tituloNoti, $mensajeNoti));

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
