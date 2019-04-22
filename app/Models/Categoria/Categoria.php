<?php

namespace App\Models\Categoria;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //protected $table = 'categorias';

    protected $fillable = ['nombre','descripcion','condicion'];

    public function articulos(){
     return $this->hasMany('App\Models\Articulo\Articulo');
    }    

}
