<?php

namespace App\Traits;

use GuzzleHttp\Client;


trait ConsumesExternalServices
{

    public function makeRequest($method, $requestUrl, 
        $queryParams = [], $formParams = [], $headers = [], $isJsonRequest = false)
    {

        //creamos el cliente con la uri base
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        //resolver la autorizacion -> Método OPCIONAL
        /*
        if(method_exists($this, 'resolveAuthorization')){
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }
        */

        $response = $client->request($method, $requestUrl, [
            'headers' => is_array($headers) ? $headers : [],
            'query' => $queryParams,
        ]);

        $response = $response->getBody()->getContents();

        //conversion de la respuesta dependiendo del servicio -> Método OPCIONAL
        if(method_exists($this, 'decodeResponse')){
            $response = $this->decodeResponse($response);
        }

        return $response;
    }


}