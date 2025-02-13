<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\OficinaFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RecordsAuditLogs;

class Oficina extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'oficinas';

    protected $fillable = [
        'id',
        'oficina',
        'encargado'
    ];

    // protected static function newFactory(): OficinaFactory
    // {
    //     // return OficinaFactory::new();
    // }
}
