<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public function setIdParadaAnteriorAttribute($id)
    {
        $this->attributes['id_parada_anterior'] = $id;
        if ($id) {
            $anterior = Parada::find($id);
            $anterior->attributes['id_parada_siguiente'] = $this->id;
            $anterior->save();
        }
    }

    public function setIdParadaSiguienteAttribute($id)
    {
        $this->attributes['id_parada_siguiente'] = $id;
        if ($id) {
            $siguiente = Parada::find($id);
            $siguiente->attributes['id_parada_anterior'] = $this->id;
            $siguiente->save();
        }
    }

    /*
    @param Builder $query Query Builder
    @param number $latitude
    @param number $longitude
    @param number $radius in meters to point

    filtra resultados a estaciones cerca de las coordenadas dadas, con un radio
    de $radius de tolerancia
    */
    public function scopeCercaDe(Builder $query, $lat, $lng, $radius=200)
    {
        $lngRadius = $latRadius = $radius * 360 / 40075000;

        $query->join(DB::raw('estaciones as e'), 'e.id', '=', 'id_estacion');

        $query->whereBetween('e.lat', [$lat - $latRadius, $lat + $latRadius]);
        $query->whereBetween('e.lng', [$lng - $lngRadius, $lng + $lngRadius]);
        /*
        Query realizada
        WHERE lat BETWEEN ($lat - $latRadius, $lat + $latRadius)
        WHERE lng BETWEEN ($lng - $lngRadius, $lng + $lngRadius)
        */
    }


    public function scopeConecta($query, $coordenadasOrigen, $coordenadasDestino)
    {
        $ids = [];
        // obtener paradas agrupadas por rutas
        $paradasCercadeOrigen = Parada::cercaDe($coordenadasOrigen['lat'], $coordenadasOrigen['lng'])
            ->groupBy(DB::raw('paradas.id_ruta, paradas.id'))
            ->get([
                'paradas.id',
                'paradas.id_ruta',
                'paradas.id_parada_siguiente',
                'paradas.id_parada_anterior'
            ]);
        $paradasCercadeDestino = Parada::cercaDe($coordenadasDestino['lat'], $coordenadasDestino['lng'])
            ->groupBy(DB::raw('paradas.id_ruta, paradas.id'))
            ->get([
                'paradas.id',
                'paradas.id_ruta',
                'paradas.id_parada_siguiente',
                'paradas.id_parada_anterior'
            ]);

        foreach ($paradasCercadeDestino as $paradaCercaDeDestino) {
            foreach ($paradasCercadeOrigen as $paradaCercaDeOrigen) {
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
            } else {
                return $this->llevaHacia($parada->paradaAnterior);
            }
        } else {
            return false;
        }
    }
}
