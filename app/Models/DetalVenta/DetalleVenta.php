<?php

namespace App\Models\DetalVenta;

use Illuminate\Database\Eloquent\Model;

class DetalleVenta extends Model
{
    protected $table = 'detalle_ventas';
    protected $fillable = [
        'idventa', 
        'idarticulo',
        'cantidad',
        'precio',
        'descuento'
    ];
    public $timestamps = false;
}
