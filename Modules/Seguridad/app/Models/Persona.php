<?php

namespace Modules\Seguridad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Seguridad\Database\Factories\PersonaFactory;

class Persona extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'personas'; // Nombre de la tabla

    protected $fillable = ['unidad_organica_id', 'nombres', 'apellidos', 'tipo_documento_identidad_id', 'numero_documento', 'edad', 'telefono', 'direccion'];

    // protected static function newFactory(): PersonaFactory
    // {
    //     // return PersonaFactory::new();
    // }
}
