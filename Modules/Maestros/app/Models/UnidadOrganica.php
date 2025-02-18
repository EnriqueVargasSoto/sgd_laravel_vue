<?php

namespace Modules\Maestros\Models;

use App\Traits\RecordsAuditLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Maestros\Database\Factories\UnidadOrganicaFactory;

class UnidadOrganica extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'unidades_organicas'; // Nombre de la tabla

    protected $fillable = ['id', 'tipo_unidad_organica_id', 'padre_id', 'tipo_derivacion_id', 'nombre'];

    // protected static function newFactory(): UnidadOrganicaFactory
    // {
    //     // return UnidadOrganicaFactory::new();
    // }
}
