<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    public $timestamps = false; // Desactiva created_at y updated_at

    /*public function pedidos()
    {
        return $this->belongsTo(Pedido::class);  // REVISARRRR PORQUE ES DE UNO A UNO <----------------------------------------
    }*/

    protected $fillable = [
        'placa',
        'color',
        'modelo',
        'ano',
        'tipo', //enum carro camioneta moto paqueteria
    ];

    
}
