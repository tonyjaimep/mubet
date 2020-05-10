<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Geocoder\Geocoder;

class Estacion extends Model
{
    protected $table = "estaciones";

    public $timestamps = false;

    public function rutas()
    {
        return $this->hasManyThrough(Ruta::class, Parada::class, 'id_ruta', 'id_estacion');
    }

    /*
    @param Builder $query Query Builder
    @param number $latitude
    @param number $longitude
    @param number $radius in meters to point

    filtra resultados a estaciones cerca de las coordenadas dadas, con un radio
    de $radius de tolerancia
    */
    public function scopeNear($query, $lat, $lng, $radius=200)
    {
        $lngRadius = $latRadius = $radius * 360 / 40075000;

        $query->whereBetween('lat', [$lat - $latRadius, $lat + $latRadius]);
        $query->whereBetween('lng', [$lng - $lngRadius, $lng + $lngRadius]);
        /*
        Query realizada
        WHERE lat BETWEEN ($lat - $latRadius, $lat + $latRadius)
        WHERE lng BETWEEN ($lng - $lngRadius, $lng + $lngRadius)
        */
    }

    public function connects()
    {

    }

    private function updateCoordinates()
    {
        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));
        $result = $geocoder->getCoordinatesForAddress($this->attributes['direccion']);
        if ($result) {
            $this->attributes['lat'] = $result['lat'];
            $this->attributes['lng'] = $result['lng'];
        }
    }

    public function setDireccionAttribute($address)
    {
        $this->attributes['direccion'] = $address;
        $this->updateCoordinates();
    }
}
