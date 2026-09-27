<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    protected $table = 'tipo_vehiculo';
    public $timestamps = false;

    protected $fillable = [
        'nombre_tipo',
    ];
}