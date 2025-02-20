<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Modules\Seguridad\Models\Modulo;
use Modules\Seguridad\Models\Persona;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasRoles, HasFactory, Notifiable, SoftDeletes;

    protected $connection = 'pgsql';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'persona_id',
        'email',
        'host',
        'mac',
        'ip',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getModules($roleId)
    {
        // Obtiene el rol con sus permisos
        $role = Role::with('permissions')->find($roleId);

        if (!$role) {
            return response()->json(['error' => 'Rol no encontrado'], 404);
        }

        // Obtiene los permisos del rol
        $rolePermissions = $role->permissions;

        // Obtener todos los módulos con sus submódulos
        //$modules = Modulo::with('submodulos.submodulos')->whereNull('padre_id')->get();
        $modules = Modulo::with('submodulos.submodulos')->get();

        // Filtrar solo los módulos principales con permisos
        $filteredModules = $modules->filter(function ($module) use ($rolePermissions) {
            if ($module->padre_id !== null) {
                return false; // Solo dejamos módulos principales (padres)
            }

            // Verificar permisos en el módulo padre
            $hasPermission = $rolePermissions->contains('modulo_id', $module->id);

            // Filtrar submódulos y submódulos de submódulos con permisos
            $filteredSubmodules = $this->filterSubmodules($module->submodulos, $rolePermissions);

            // Asignamos los submódulos filtrados
            $module->setRelation('submodulos', $filteredSubmodules->values());

            return $hasPermission || $filteredSubmodules->isNotEmpty();
        });

        return $filteredModules->map(function ($module) {
            return $this->getModuleWithSubmodules($module);
        });
    }

    protected function filterSubmodules($submodules, $rolePermissions)
    {
        return $submodules->filter(function ($submodule) use ($rolePermissions) {
            // Verifica si el usuario tiene permiso en el submódulo
            $hasPermission = $rolePermissions->contains('modulo_id', $submodule->id);

            // Filtrar submódulos dentro del submódulo actual (tercer nivel)
            $filteredSubSubmodules = $this->filterSubmodules($submodule->submodulos, $rolePermissions);

            // Asignamos los submódulos filtrados
            $submodule->setRelation('submodulos', $filteredSubSubmodules->values());

            return $hasPermission || $filteredSubSubmodules->isNotEmpty();
        });
    }

    protected function getModuleWithSubmodules($module)
    {
        return
            $module
            //'submodulos' => $module->submodulos,
        ;
    }


    protected function getModuleWithPermissions($module)
    {
        $permissions = $this->getPermissionsViaRoles()->filter(function ($permission) use ($module) {
            return strpos($permission->slug, $module->name) !== false;
        });

        return [
            'module' => $module,
            /* 'permissions' => $permissions */
        ];
    }

    public function persona() {
        return $this->belongsTo(Persona::class);
    }


}
