<?php

// app/Models/Administrador.php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Administrador extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'administradores';
    //protected $table = null;
    // Fuerza el nombre correcto
    public function getTable()
    {
        return $this->collection;
    }


    protected $fillable = [
        'nombre',
        'apellido',
        'usuario',
        'imagen',
        'contrasena',
        'correo',
        'telefono',
        'tipo',
        'estado',
    ];

    protected $hidden = ['contrasena'];

    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
