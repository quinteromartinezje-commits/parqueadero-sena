<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Tarifa
 * 
 * Representa la tabla de tarifas del sistema de parqueadero.
 * Cada tarifa está asociada a un tipo de vehículo y contiene
 * los valores para hora, fracción y recargo nocturno.
 * 
 * @package App\Models
 * @author Parking Como en Casa Team
 * @version 1.0
 */
class Tarifa extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     * 
     * @var string
     */
    protected $table = 'tarifas';

    /**
     * Indica si el modelo usa timestamps (created_at, updated_at).
     * En este caso no se utilizan.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * Atributos que pueden ser asignados masivamente.
     * 
     * @var array
     */
    protected $fillable = [
        'id_tipo_vehiculo',
        'tarifa_hora',
        'tarifa_fraccion',
        'recargo_nocturno',
        'tarifa_vigente',
    ];

    /**
     * Relación con el modelo TipoVehiculo.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoVehiculo()
    {
        return $this->belongsTo(TipoVehiculo::class, 'id_tipo_vehiculo', 'id');
    }
}