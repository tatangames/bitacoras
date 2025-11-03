<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAcceso extends Model
{
    use HasFactory;
    protected $table = 'tipo_acceso';
    public $timestamps = false;
}
