<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\TipoMovimientoFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RecordsAuditLogs;

class TipoMovimiento extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'tipo_movimientos';

    protected $fillable = ['id', 'tipo'];

    // protected static function newFactory(): TipoMovimientoFactory
    // {
    //     // return TipoMovimientoFactory::new();
    // }
}
