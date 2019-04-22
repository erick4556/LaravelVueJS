<?php

namespace App\Models\DetalIngreso;

use Illuminate\Database\Eloquent\Model;

class DetalleIngreso extends Model
{
    protected $table = 'detalle_ingresos';
    protected $fillable = [
        'idingreso', 
        'idarticulo',
        'cantidad',
        'precio'
    ];
    public $timestamps = false;
}
