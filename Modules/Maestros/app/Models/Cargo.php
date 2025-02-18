<?php

namespace Modules\Maestros\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Maestros\Database\Factories\CargoFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\RecordsAuditLogs;

class Cargo extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'cargos'; // Nombre de la tabla

    protected $fillable = ['cargo', 'slug', 'descripcion'];

    // protected static function newFactory(): CargoFactory
    // {
    //     // return CargoFactory::new();
    // }
}
