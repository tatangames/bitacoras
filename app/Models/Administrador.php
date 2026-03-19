<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Administrador extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasRoles;

    protected $table = 'administrador';
    public $timestamps = false;

    protected $guard_name = 'admin';

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'id_unidad');
    }

    protected $fillable = [
        'nombre',
        'usuario',
        'password',
        'activo',
        'id_unidad',
        'onesignal_player_id',
    ];


}
