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
