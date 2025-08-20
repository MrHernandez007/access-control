<?php

namespace App\Models;

use App\Models\Residente;
use App\Models\Visitante;
use MongoDB\Laravel\Eloquent\Model;

class Visita extends Model
{
    protected $collection = 'visitas';
    protected $connection = 'mongodb';

    protected $fillable = [
        'visitante_id',
        'residente_id',
        'dia_visita',
        'hora_llegada',
        'hora_salida',
        'estado',
    ];

    public function visitante()
    {
        return $this->belongsTo(Visitante::class);
    }

    public function residente()
    {
        return $this->belongsTo(Residente::class);
    }
}

