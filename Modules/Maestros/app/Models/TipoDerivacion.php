<?php

namespace Modules\Maestros\Models;

use App\Traits\RecordsAuditLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Maestros\Database\Factories\TipoDerivacionFactory;

class TipoDerivacion extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'tipos_derivacion'; // Nombre de la tabla

    protected $fillable = [
        'id',
        'derivacion',
        'slug',
        'niveles',
        'estado'
    ];

    // protected static function newFactory(): TipoDerivacionFactory
    // {
    //     // return TipoDerivacionFactory::new();
    // }
}
