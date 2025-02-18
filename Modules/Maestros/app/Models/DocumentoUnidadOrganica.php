<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\DocumentoUnidadOrganicaFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RecordsAuditLogs;

class DocumentoUnidadOrganica extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'documentos_unidad_organica'; // Nombre de la tabla

    protected $fillable = [
        'id',
        'unidad_organica_id',
        'tipo_documento_id',
        'tipo'
    ];

    // protected static function newFactory(): DocumentoUnidadOrganicaFactory
    // {
    //     // return DocumentoUnidadOrganicaFactory::new();
    // }
}
