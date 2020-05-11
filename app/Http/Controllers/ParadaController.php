<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Geocoder\Geocoder;

use App\Parada;

class ParadaController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'origen' => 'required',
            'destino' => 'required'
        ]);

        $probablesCoordenadasOrigen = explode(' ', $request->origen);
        $coordenadasOrigen = [];
        $coordenadasDestino = [];

        /* si el origen está dado como coordenadas */
        if (count($probablesCoordenadasOrigen) == 2
        && is_numeric($probablesCoordenadasOrigen[0])
        && is_numeric($probablesCoordenadasOrigen[1])) {
            $coordenadasOrigen['lat'] = $probablesCoordenadasOrigen[0];
            $coordenadasOrigen['lng'] = $probablesCoordenadasOrigen[1];
        } else {
            // obtener las coordenadas de la dirección dada
            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocoder->setApiKey(config('geocoder.key'));
            $result = $geocoder->getCoordinatesForAddress($request->origen);
            if ($result) {
                $coordenadasOrigen['lat'] = $result['lat'];
                $coordenadasOrigen['lng'] = $result['lng'];
            } else {
                return response(json_encode(['success' => false, 'message' => 'Origen no encontrado']), 404);
            }
        }

        if (!isset($client)) {
            $client = new \GuzzleHttp\Client();
            $geocoder = new Geocoder($client);
            $geocoder->setApiKey(config('geocoder.key'));
        }

        $result = $geocoder->getCoordinatesForAddress($request->destino);

        if ($result) {
            $coordenadasDestino['lat'] = $result['lat'];
            $coordenadasDestino['lng'] = $result['lng'];
        } else {
            return response(json_encode(['success' => false, 'message' => 'Destino no encontrado']), 404);
        }

        $paradasResultado = Parada::conecta($coordenadasOrigen, $coordenadasDestino);
        $paradasResultado = $paradasResultado->with(['ruta', 'estacion'])->get();

        if (count($paradasResultado)) {
            return response()->json([
                'success' => true,
                'data' => $paradasResultado
            ]);
        }
        return response(json_encode(['success' => false, 'message' => 'No se encontró una ruta entre el origen y el destino dados.']), 404);
    }
}
