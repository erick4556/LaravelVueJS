<?php

namespace App\Models\Proveedor;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedors';
    protected $fillable = [
        'id', 'contacto', 'telefono_contacto'
    ];
 
    public $timestamps = false;
 
    public function persona()
    {
        return $this->belongsTo('App\Models\Persona\Persona');
    }
}
