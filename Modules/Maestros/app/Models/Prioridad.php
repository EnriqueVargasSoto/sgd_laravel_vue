<?php

namespace Modules\Maestros\Models;

use App\Traits\RecordsAuditLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Maestros\Database\Factories\PrioridadFactory;

class Prioridad extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'prioridades'; // Nombre de la tabla

    protected $fillable = [
        'id',
        'prioridad',
        'slug',
        'estado'
    ];

    // protected static function newFactory(): PrioridadFactory
    // {
    //     // return PrioridadFactory::new();
    // }
}
