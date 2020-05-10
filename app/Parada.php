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

    public function paradaSiguiente()
    {
        return $this->belongsTo(Parada::class, 'id_parada_siguiente');
    }

    public function paradaAnterior()
    {
        return $this->belongsTo(Parada::class, 'id_parada_anterior');
    }

    public function scopeConecta($query, $coordenadasOrigen, $coordenadasDestino)
    {
        $ids = [];
        // obtener paradas agrupadas por rutas
        $paradasOrigen = Parada::near($coordenadasOrigen)
            ->groupBy('id_ruta')
            ->get(['id', 'id_ruta_siguiente', 'id_ruta_anterior']);
        $paradasDestino = Parada::near($coordenadasDestino)
            ->groupBy('id_ruta')
            ->get(['id', 'id_ruta_siguiente', 'id_ruta_anterior']);

        foreach ($paradasDestino as $paradaCercaDeDestino) {
            foreach ($paradasOrigen as $paradaCercaDeOrigen) {
                if ($paradaCercaDeOrigen->llevaHacia($paradaCercaDeDestino)) {
                    array_push($ids, $paradaCercaDeOrigen->id);
                }
            }
        }

        $query->whereIn('id', $ids);
    }

    public function llevaHacia($parada)
    {
        if ($parada->id_parada_anterior) {
            if ($parada->id_parada_anterior == $this->id
                || $parada->id_parada_anterior == $this->id_parada_siguiente) {
                return true;
                delete ()
            } else {
                return $this->llevaHacia($parada->paradaAnterior);
            }
        } else {
            return false;
        }
    }
}
