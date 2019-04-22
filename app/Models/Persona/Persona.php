<?php

namespace App\Models\Persona;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre','tipo_documento','num_documento','direccion','telefono','email'];

    public function proveedor(){
        return $this->hasOne('App\Models\Proveedor\Proveedor'); //Esta relacionado con un solo proveedor
    }

    public function user(){
        return $this->hasOne('App\User');
    } 

}
