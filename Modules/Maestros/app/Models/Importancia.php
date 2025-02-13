<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\ImportanciaFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RecordsAuditLogs;

class Importancia extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'importancias';

    protected $fillable = ['id', 'importancia'];

    // protected static function newFactory(): ImportanciaFactory
    // {
    //     // return ImportanciaFactory::new();
    // }
}
