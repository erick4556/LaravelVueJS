<?php

namespace App\Models\Rol;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    protected $fillable = ['nombre','descripcion','condicion'];
    public $timestamps = false;

    public function users(){ //Un rol puede tener varios usuarios
        return $this->hasMany('App\User');
    }

}
