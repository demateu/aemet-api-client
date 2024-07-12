<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;
use Illuminate\Support\Facades\Http;

use GuzzleHttp\Client;

class WeatherService
{
    use ConsumesExternalServices;

    protected $baseUri;
    protected $api_key;

    public function __construct(){
        $this->baseUri = config('services.aemet.base_uri');
        $this->api_key = config('services.aemet.key');//siempre será la misma
    }

    /**
     * @author demateu
     * 
     * Autorizamos el endpoint pasando el api_key + el endpoint
     * nos devuelve un json con la ruta que contiene la data (datos)
     * @return Response
     */
    public function resolveAuthorization($path)//(&$queryParams, &$formParams, &$headers)
    {
        return $this->makeRequest(
            'GET',
            $path,
            [
                'api_key' => $this->api_key,
            ],// los query params, headers
            [
            ],
        );
    }

    
    public function showMunicipios($uri_municipios){
        $isJsonRequest = true;
        $formParams = [];

        //creamos el cliente con la uri base
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);
        
        $response = $client->request('GET', $uri_municipios, [
            //si $isJsonRequest es true, enviamos json, sino enviamos form_params
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            //'headers' => $headers,
            'headers' => [],
            'query' => [],
        ]);
        

        // Convertir de bytes a cadena (UTF-8) -> utf8_decode($data)
        $response = json_decode(utf8_decode($response->getBody()->getContents()), true);

        return $response;//array -> enviar al select, vista?
    }


    /**
     * @author demateu
     * 
     * obtener el carrito desde la cookie, o crearlo si no existe
     */
    public function getWeatherForCity($uri_tiempo_municipio){
        $isJsonRequest = true;
        $formParams = [];

        //creamos el cliente con la uri base
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);
        
        $response = $client->request('GET', $uri_tiempo_municipio, [
            //si $isJsonRequest es true, enviamos json, sino enviamos form_params
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            //'headers' => $headers,
            'headers' => [],
            'query' => [],
        ]);

        // Convertir de bytes a cadena (UTF-8) -> utf8_decode($data)
        $response = json_decode(utf8_decode($response->getBody()->getContents()), true);

        return $response;//array -> enviar al select, vista?
    }


    /**
     * Recibe el objeto de la respuesta y la devuelve decodificada
     * 
     * convierte el formato JSON en un objeto PHP o en un array asociativo
     * dependiendo de los parámetros opcionales
     */
    public function decodeResponse($response)
    {
        //return json_decode($response);
        return json_decode($response, true);
    }
  
    

}