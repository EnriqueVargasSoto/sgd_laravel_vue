<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\EstadoFactory;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RecordsAuditLogs;

class Estado extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'estados';

    protected $fillable = ['id', 'name'];

    // protected static function newFactory(): EstadoFactory
    // {
    //     // return EstadoFactory::new();
    // }
}
