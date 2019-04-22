<?php

namespace App\Models\Venta;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable =[
        'idcliente', 
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];
}
