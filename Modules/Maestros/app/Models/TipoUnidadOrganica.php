<?php

namespace Modules\Maestros\Models;

use App\Models\User;
use App\Traits\RecordsAuditLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Modules\Maestros\Database\Factories\TipoUnidadOrganicaFactory;

class TipoUnidadOrganica extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'tipos_unidad_organica'; // Nombre de la tabla

    protected $fillable = ['id', 'nombre', 'slug'];

    // protected static function newFactory(): TipoUnidadOrganicaFactory
    // {
    //     // return TipoUnidadOrganicaFactory::new();
    // }
    public function personas() {
        return $this->hasMany(User::class);
    }
}
