<?php

namespace Modules\Seguridad\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $permissions = [
            'rol-listar',
            'rol-crear',
            'rol-editar',
            'rol-eliminar',
            'usuario-listar',
            'usuario-crear',
            'usuario-editar',
            'usuario-eliminar',
            'permisos-listar',
            'permisos-crear',
            'permisos-editar',
            'permisos-eliminar'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
