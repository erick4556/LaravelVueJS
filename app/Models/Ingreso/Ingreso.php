<?php

namespace App\Models\Ingreso;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $fillable = [ 'idproveedor', 
        'idusuario',
        'tipo_comprobante',
        'serie_comprobante',
        'num_comprobante',
        'fecha_hora',
        'impuesto',
        'total',
        'estado'
    ];

    public function usuario(){ //Obtener el usuario que ha registrado el ingreso
        return $this->belongsTo('App\User');
    }

    public function proveedor(){ //Indicar cual es el proveedor que ha abastecido
        return $this->belongsTo('App\Models\Proveedor\Proveedor');
    }

}
