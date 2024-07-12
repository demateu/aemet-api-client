<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/* Api Client */
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/* Models */
use App\Models\Municipio;
use App\Models\Weather;

/* Services */
use App\Services\WeatherService;



class WeatherController extends Controller
{

    //inyectamos el Servicio para consumir la API del tiempo
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }


    /**
     * @author demateu
     * /api/maestro/municipios GET
     */
    public function mostrarMunicipios()
    {
        $path = 'opendata/api/maestro/municipios';

        try {
            $municipios_response_authorized = $this->weatherService->resolveAuthorization($path);
            
            if($municipios_response_authorized['estado'] === 200){
                $uri = $municipios_response_authorized['datos'];

                //Separar URI https://opendata.aemet.es/opendata/sh/ed0e8f7f
                $parts = explode('/', $uri);

                // La parte restante es la ruta opendata/sh/ed0e8f7f
                $path = implode('/', array_slice($parts, 3));

                $municipios_all = $this->weatherService->showMunicipios($path); //array -> enviar al select, vista?
                //dd($municipios_all[0]['id_old']); -> es un array de arrays

                $municipiosStdClass = array_map(function($item) { //object of type stdClass
                    return (object) $item;
                }, $municipios_all);

                        // Convertir cada stdClass a una instancia del modelo Municipio
                $municipios = array_map(function($municipioStdClass) {
                    // Usar el método fill para asignar atributos de manera segura
                    $municipio = new Municipio();
                    $municipio->fill((array)$municipioStdClass);
                    return $municipio;
                }, $municipiosStdClass);


                return view('home', [
                    'municipios' => $municipios,//objeto del Modelo
                ]);
            }

            
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }


    /**
     * @author demateu
     * /api/maestro/municipios GET
     * 
     * api/prediccion/especifica/municipio/diaria/{municipio}
     * https://opendata.aemet.es/opendata/api/prediccion/especifica/municipio/diaria/44004
     */
    public function mostrarTiempoMunicipio(Request $request)//pasar id old del municipio
    {
        $municipio_id = $request->municipio;
        $path = "opendata/api/prediccion/especifica/municipio/diaria/{$municipio_id}";

            // Aquí puedes realizar la llamada a la API utilizando el path generado
            // Por ejemplo:
            $tiempo_response_authorized = $this->weatherService->resolveAuthorization($path);

            //PONER IF PARA estado => 200, descripcion => "exito", el endpoint es datos => https://opendata.aemet.es/opendata/sh/cc64e54f
            if($tiempo_response_authorized['estado'] === 200){
                $uri = $tiempo_response_authorized['datos'];

                //Separar URI https://opendata.aemet.es/opendata/sh/cc64e54f
                $parts = explode('/', $uri);

                // La parte restante es la ruta opendata/sh/cc64e54f
                $path = implode('/', array_slice($parts, 3));

                //AQUI HAY QUE LLAMAR AL METODO QUE LLAME EL ENDPOINT QUE NOS DARA LOS DATOS DEL TIEMPO
                $tiempo_municipio_array = $this->weatherService->getWeatherForCity($path);

                $tiempo = new Weather();
                $tiempo->fill((array)$tiempo_municipio_array);

                $tiempo = json_decode($tiempo);

                //fecha prediccion
                $fechaStringPrediccion = $tiempo[0]->elaborado;
                $fechaCarbonPrediccion = Carbon::parse($fechaStringPrediccion)->format('d-m-Y');

                //nombre municipio
                $municipio = $tiempo[0]->nombre;

                //temperaturas
                $maxima = $tiempo[0]->prediccion->dia[0]->temperatura->maxima;
                $minima = $tiempo[0]->prediccion->dia[0]->temperatura->minima;


                // Retornar la vista con los datos del tiempo del municipio
                return view('tiempo', [
                    //'tiempo' => $tiempo,//objeto del Modelo
                    'fecha' => $fechaCarbonPrediccion,
                    'municipio' => $municipio,
                    'maxima' => $maxima,
                    'minima' => $minima,
                ]);

            }
        

    }

}