<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    // Desactivar la protección de asignación masiva
    protected $guarded = [];

    // Indicar que no use una tabla de base de datos
    public $timestamps = false;
    protected $table = false;


    /**
     * Declaro los atributos para aportar claridad al código
     * pero la asignacion de atributos será manualmente y esta parte es opcional
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'nombre',
        'id_old',//este id es el que pedirá el endpoint para devolver el tiempo
        'latitud',
        'url',
        'latitud_dec',
        'altitud',
        'capital',
        'num_hab',
        'zona_comarcal',
        'destacada',
        'longitud_dec',
        'longitud',
    ];

    // Desactivar cualquier intento de guardar o actualizar
    public function save(array $options = [])
    {
        return false;
    }

    public function delete()
    {
        return false;
    }

}
