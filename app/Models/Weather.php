<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
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
            //...
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
