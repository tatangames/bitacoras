<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\BitacorasIncidencias;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    private function getTemaPredeterminado(){
        return Auth::guard('admin')->user()->tema;
    }


    public function indexGenerarTicket()
    {
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.ticket.generar.indexgenerarticket', compact('temaPredeterminado'));
    }


    public function generarTicket(Request $request){

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
            $dato->estado = 0;
            $dato->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }




}
