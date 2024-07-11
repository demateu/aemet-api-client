<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Rutas para borrar la caché y limpiar configuración
|--------------------------------------------------------------------------
*/
use Illuminate\Support\Facades\Artisan;
Route::get('/clear-cache', function () {
    echo Artisan::call('config:clear');
    echo Artisan::call('cache:clear');
    echo Artisan::call('route:clear');
    echo Artisan::call('config:cache');
    echo "<br>Caché borrada, Configuración limpia!!";
    //return redirect()->back();
 });


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
Route::get('/', function () {
    return view('welcome');
})->name('main');
*/
Route::get('/', 'WeatherController@mostrarMunicipios')->name('home');

Route::get('meteo', 'WeatherController@mostrarTiempoMunicipio')->name('meteo.municipio.show');