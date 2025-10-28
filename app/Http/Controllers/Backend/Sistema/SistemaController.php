<?php

namespace App\Http\Controllers\Backend\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SistemaController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexOperadores()
    {
        return view('backend.admin.configuracion.operador.vistaoperador');
    }

    public function tablaOperadores()
    {
        $arrayOperadores = Operador::orderBy('nombre', 'ASC')->get();

        return view('backend.admin.configuracion.operador.tablaoperador', compact('arrayOperadores'));
    }


    public function nuevoOperador(Request $request)
    {
        $regla = array(
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }
        DB::beginTransaction();

        try {
            $dato = new Operador();
            $dato->nombre = $request->nombre;
            $dato->save();

            DB::commit();
            return ['success' => 1];
        } catch (\Throwable $e) {
            Log::info('error ' . $e);
            DB::rollback();
            return ['success' => 99];
        }
    }

    public function infoOperador(Request $request)
    {
        $regla = array(
            'id' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }

        $info = Operador::where('id', $request->id)->first();

        return ['success' => 1, 'info' => $info];
    }

    public function actualizarOperador(Request $request)
    {
        $regla = array(
            'id' => 'required',
            'nombre' => 'required'
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()) {
            return ['success' => 0];
        }

        Operador::where('id', $request->id)->update([
            'nombre' => $request->nombre
        ]);

        return ['success' => 1];
    }




}
