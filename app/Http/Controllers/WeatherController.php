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


            //return $cities_all;
            //retornar la vista con los datos de las ciudades
        } catch (RequestException $e) {
            return $e->getMessage();
        }
    }


    //recibir datos del form por el request, validar datos...


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


                    // Convertir stdClass a array
                    //$array = json_decode(json_encode($tiempo), true);

                    // Convertir array a objeto de Laravel
                    //$objetoLaravel = Collection::make($array);
                    //dd($objetoLaravel[0]->nombre);

                //dd($tiempo[0]->elaborado);
                //$tiempo = new Collection($tiempo_municipio_array);

                            //$data = json_decode($jsonResponse);
                            /*
                            // Verificar si $data es un objeto stdClass
                            if (is_object($tiempo_municipioStdClass) && get_class($tiempo_municipioStdClass) === 'stdClass') {
                                dd('stdclass');
                            }
                            
                            // Verificar si $data es un array
                            if (is_array($tiempo_municipioStdClass)) {
                                dd('aray');
                            }
                            */
                    // Convertir el array a objeto
                    /*
                    $tiempo = (object) $tiempo_municipioStdClass;
                    if (is_object($tiempo) && get_class($tiempo) === 'stdClass') {
                        dd('stdclass');
                    }
                    */

                    // También puedes usar el método object de Collection
                    //$coleccion = new Collection($array);
                    //$objetoDesdeColeccion = $coleccion->object();

                //$tiempo = new Weather();
                //$tiempo->fill((array)$tiempo_municipioStdClass);

                // Retornar la vista con los datos del tiempo del municipio
                return view('tiempo', [
                    //'tiempo' => $tiempo,//objeto del Modelo
                    'fecha' => $fechaCarbonPrediccion,
                    'municipio' => $municipio,
                    'maxima' => $maxima,
                    'minima' => $minima,
                ]);

                /*
                  #attributes: array:1 [▼
                    0 => array:7 [▼
                    "origen" => array:6 [▶]
                    "elaborado" => "2024-07-11T18:36:08"
                    "nombre" => "Aguaviva"
                    "provincia" => "Teruel"
                    "prediccion" => array:1 [▶]
                    "id" => -21532
                    "version" => 1.0
                ]
                */

                /*
                "elaborado" => "2024-07-11T18:36:08"
                "nombre" => "Aguaviva"
                "provincia" => "Teruel"
                "prediccion" => array:1 [▼
                  "dia" => array:7 [▼
                    0 => array:10 [▼
                      "probPrecipitacion" => array:7 [▶]
                      "cotaNieveProv" => array:7 [▶]
                      "estadoCielo" => array:7 [▶]
                      "viento" => array:7 [▶]
                      "rachaMax" => array:7 [▶]
                      "temperatura" => array:3 [▶]
                      "sensTermica" => array:3 [▶]
                      "humedadRelativa" => array:3 [▶]
                      "uvMax" => 10
                      "fecha" => "2024-07-11T00:00:00"
                    ]
                    1 => array:10 [▼
                      "probPrecipitacion" => array:7 [▶]
                      "cotaNieveProv" => array:7 [▶]
                      "estadoCielo" => array:7 [▶]
                      "viento" => array:7 [▶]
                      "rachaMax" => array:7 [▶]
                      "temperatura" => array:3 [▶]
                      "sensTermica" => array:3 [▶]
                      "humedadRelativa" => array:3 [▶]
                      "uvMax" => 10
                      "fecha" => "2024-07-12T00:00:00"
                    ]
                    2 => array:10 [▶]
                    3 => array:10 [▶]
                    4 => array:10 [▶]
                    5 => array:9 [▶]
                    6 => array:9 [▶]
                  ]
                */

            }
        

    }

}