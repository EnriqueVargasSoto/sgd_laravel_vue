<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\TipoDocumentoFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RecordsAuditLogs;

class TipoDocumento extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'tipos_documento';

    protected $fillable = ['id', 'nombre', 'slug', 'estado'];

    // protected static function newFactory(): TipoDocumentoFactory
    // {
    //     // return TipoDocumentoFactory::new();
    // }

}
