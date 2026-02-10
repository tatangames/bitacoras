<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Administrador;
use App\Models\BitacorasIncidencias;
use App\Models\Unidad;
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

            $userId = auth()->id();
            $fechaActual = Carbon::now('America/El_Salvador');

            $dato = new BitacorasIncidencias();
            $dato->id_usuario = $userId;
            $dato->fecha_registro = $fechaActual;
            $dato->fecha = $request->fecha;
            $dato->tipo_incidente = $request->tipo;
            $dato->estado = 0;
            $dato->nivel = 1;
            $dato->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }



    public function indexTicketPendiente()
    {
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.ticket.pendientes.vistapendienteticket', compact('temaPredeterminado'));
    }

    public function tablaTicketPendiente()
    {
        $idusuario = Auth::id();

        $arrayBitacoraIncidencias = BitacorasIncidencias::where('id_usuario', $idusuario)
            ->orderBy('fecha', 'ASC')
            ->where('estado', 0) // pendientes
            ->get()
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
                'estado' => 1, // solucionado por usuario
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
        $temaPredeterminado =  $this->getTemaPredeterminado();

        return view('backend.admin.ticket.completados.vistacompletadoticket', compact('temaPredeterminado'));
    }


    public function tablaTicketPendientePorRevisar(Request $request)
    {
        $arrayBitacoraIncidencias = BitacorasIncidencias::where('estado', 1)
            ->orderBy('fecha', 'ASC')
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
                'estado' => 2, // ya revisado y completado por administradores
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
