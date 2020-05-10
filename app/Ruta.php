<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    public $timestamps = false;

    public function paradas()
    {
        return $this->belongsToMany(Parada::class);
    }
}
