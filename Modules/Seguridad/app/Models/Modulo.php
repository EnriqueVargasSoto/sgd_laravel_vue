<?php

namespace Modules\Seguridad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\Seguridad\Database\Factories\ModuloFactory;

use App\Traits\RecordsAuditLogs;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Modulo extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $connection = 'pgsql';

    protected $table = 'modulos'; // Nombre de la tabla

    protected $fillable = ['nombre', 'slug', 'ruta', 'descripcion', 'padre_id', 'icono', 'padre'];

    /**
     * Relación: Un módulo puede tener múltiples submódulos.
     */
    public function submodulos()
    {
        return $this->hasMany(Modulo::class, 'padre_id');
    }

    /**
     * Relación: Un submódulo pertenece a un módulo padre.
     */
    public function moduloPadre()
    {
        return $this->belongsTo(Modulo::class, 'padre_id');
    }

    public function permisos() {
        return $this->hasMany(Permission::class, 'modulo_id');
    }
    // protected static function newFactory(): ModuloFactory
    // {
    //     // return ModuloFactory::new();
    // }
}
