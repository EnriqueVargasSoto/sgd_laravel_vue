<?php

namespace Modules\Seguridad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Modules\Seguridad\Database\Factories\ModuloFactory;

use App\Traits\RecordsAuditLogs;

class Modulo extends Model
{
    use HasFactory, RecordsAuditLogs, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $table = 'modulos'; // Nombre de la tabla
    protected $fillable = ['nombre', 'descripcion', 'url', 'parent_id'];

    /**
     * Relación: Un módulo puede tener múltiples submódulos.
     */
    public function submodulos()
    {
        return $this->hasMany(Modulo::class, 'parent_id');
    }

    /**
     * Relación: Un submódulo pertenece a un módulo padre.
     */
    public function moduloPadre()
    {
        return $this->belongsTo(Modulo::class, 'parent_id');
    }

    // protected static function newFactory(): ModuloFactory
    // {
    //     // return ModuloFactory::new();
    // }
}
