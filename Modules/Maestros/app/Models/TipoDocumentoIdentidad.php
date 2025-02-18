<?php

namespace Modules\Maestros\Models;

use App\Traits\RecordsAuditLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Maestros\Database\Factories\TipoDocumentoIdentidadFactory;

class TipoDocumentoIdentidad extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'tipos_documento_identidad'; // Nombre de la tabla

    protected $fillable = [
        'id',
        'tipo',
        'slug',
        'estado'
    ];

    // protected static function newFactory(): TipoDocumentoIdentidadFactory
    // {
    //     // return TipoDocumentoIdentidadFactory::new();
    // }
}
