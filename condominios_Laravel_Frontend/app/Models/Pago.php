<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Residente;
use MongoDB\BSON\Decimal128;

class Pago extends Model
{
    protected $collection = 'pagos';
    protected $connection = 'mongodb';

    protected $fillable = [
        'residente_id',
        'vivienda_id',
        'mes',
        'cantidad',
        'estado', //"pendiente", "pagado", "vencido"
        'tarjeta', //"Crédito", "Débito", "Efectivo", etc.
        'fecha_generado',
        'fecha_pago',
        'referencia_pago',
        'descripcion',
    ];

    protected $casts = [
        'fecha_generado' => 'datetime',
        'fecha_pago' => 'datetime',
        'cantidad' => 'float',
    ];

    public function residente()
    {
        return $this->belongsTo(Residente::class);
    }

    // Si tienes viviendas en otra colección
    public function vivienda()
    {
        return $this->belongsTo(Vivienda::class);
    }

    public function getCantidadAttribute($value)
{
    if ($value instanceof Decimal128) {
        return (float) $value->jsonSerialize(); // Convierte Decimal128 a float seguro
    }

    return (float) $value; // Por si ya es float
}
}
