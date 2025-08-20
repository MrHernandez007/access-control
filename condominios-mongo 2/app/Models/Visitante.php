<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Visita;

class Visitante extends Model
{
    protected $collection = 'visitantes';
    protected $connection = 'mongodb';

    protected $casts = [
        'vehiculo' => 'array', // si embebes el vehículo aquí
    ];

    

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'tipo',
        'vehiculo',
        'estado',
        'residente_id',  // <--- nuevo campo

    ];

    public function getVehiculoAttribute($value)
{
    if (is_array($value)) return $value;

    if (is_string($value)) {
        return json_decode($value, true) ?? [];
    }

    return [];
}

    public function residente()
    {
        return $this->belongsTo(Residente::class);
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }

    
}

