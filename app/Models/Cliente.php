<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo que representa la tabla 'cliente' de la base de datos mydb.
 */
class Cliente extends Model
{
    // 1. Le decimos a Laravel el nombre exacto de la tabla en MySQL
    protected $table = 'cliente';

    // 2. Como tu tabla no tiene las columnas 'created_at' y 'updated_at', 
    // le decimos a Laravel que NO las use.
    public $timestamps = false;

    // 3. Definimos la clave primaria (Laravel asume que es 'id', pero es bueno explicitarlo)
    protected $primaryKey = 'id';

    // 4. Campos que permitimos llenar desde los formularios (Seguridad)
    protected $fillable = [
        'nombre_completo',
        'documento',
        'telefono',
        'correo',
    ];
}