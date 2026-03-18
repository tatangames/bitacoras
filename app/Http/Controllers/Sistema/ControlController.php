<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Extras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ControlController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function indexRedireccionamiento(){
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.roles.index');
        }

        if ($user->hasRole('editor')) {
            return redirect()->route('admin.registro.novedades.acceso.index');
        }

        if ($user->hasRole('ticket')) {
            return redirect()->route('admin.ticket.generar.index');
        }

        return redirect()->route('no.permisos.index');
    }

    public function indexSinPermiso(){
        return view('errors.403');
    }



}
