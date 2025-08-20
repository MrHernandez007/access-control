<?php

use App\Models\Residente;
use App\Models\Visitante;
use MongoDB\Laravel\Eloquent\Model;

class GeneradorQR extends Model
{
    protected $collection = 'generador_qr';
    protected $connection = 'mongodb';

    public function residente()
    {
        return $this->belongsTo(Residente::class);
    }

    public function visitante()
    {
        return $this->belongsTo(Visitante::class);
    }
}
