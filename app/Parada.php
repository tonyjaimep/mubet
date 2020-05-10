<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parada extends Model
{
    public $timestamps = false;

    public function estacion()
    {
        return $this->belongsTo(Estacion::class, 'id_estacion');
    }

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }
}
