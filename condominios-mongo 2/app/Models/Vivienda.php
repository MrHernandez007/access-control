<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Vivienda extends Model
{
    protected $collection = 'viviendas';
    protected $connection = 'mongodb';

    protected $fillable = [
        'numero',
        'tipo',
        'calle',
    ];

    public function residentes()
    {
        return $this->hasMany(Residente::class);
    } 
}



