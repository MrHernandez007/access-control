<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

// use Jenssegers\Mongodb\Eloquent\Model as Eloquent; Tienes instalado jenssegers/mongodb versión 5.4.1 Esta versión ya no usa Jenssegers\Mongodb\Eloquent\Model Ahora usa el nuevo namespace oficial: 
//MongoDB\Laravel\Eloquent\Model


class Residente extends Model
{
    protected $collection = 'residentes';
    protected $connection = 'mongodb';

    protected $fillable = [
        'vivienda_id',
        'nombre',
        'apellido',
        'usuario',
        'imagen',
        'contrasena',
        'correo',
        'telefono',
        'estado'
    ];

    public function vivienda()
    {
        return $this->belongsTo(Vivienda::class, 'vivienda_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function visitantes()
    {
        return $this->hasMany(Visitante::class);
    }

    public function visitas()
    {
        return $this->hasMany(Visita::class);
    }

    public function qrs()
    {
        return $this->hasMany(GeneradorQR::class);
    }
}
