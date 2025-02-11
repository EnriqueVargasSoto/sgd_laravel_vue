<?php

namespace Modules\Seguridad\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Seguridad\Models\Modulo;
use Spatie\Permission\Models\Permission;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        Modulo::create([
            'name' => 'Modulos',
            'slug' => 'modulos',
            'description' => 'Descripcion tal',
        ]);
        Modulo::create([
            'name' => 'Usuarios',
            'slug' => 'usuarios',
            'description' => 'usuarios tal',
        ]);
        Modulo::create([
            'name' => 'Roles y Permisos',
            'slug' => 'roles-permisos',
            'description' => 'roles tal',
        ]);
        Modulo::create([
            'name' => 'Roles',
            'slug' => 'roles',
            'description' => 'roles tal',
            'parent_id' => 3
        ]);
        Modulo::create([
            'name' => 'Permisos',
            'slug' => 'permisos',
            'description' => 'permisos tal',
            'parent_id' => 3
        ]);


        $permissions = [
            ['slug'=>'listar','name'=>'modulo-listar'],
            ['slug'=>'crear','name'=>'modulo-crear'],
            ['slug'=>'editar','name'=>'modulo-editar'],
            ['slug'=>'eliminar','name'=>'modulo-eliminar']
        ];

        $permissionsUsers = [
            ['slug'=>'listar','name'=>'usuario-listar'],
            ['slug'=>'crear','name'=>'usuario-crear'],
            ['slug'=>'editar','name'=>'usuario-editar'],
            ['slug'=>'eliminar','name'=>'usuario-eliminar']
        ];

        $permissionsRoles = [
            ['slug'=>'listar','name'=>'rol-listar'],
            ['slug'=>'crear','name'=>'rol-crear'],
            ['slug'=>'editar','name'=>'rol-editar'],
            ['slug'=>'eliminar','name'=>'rol-eliminar']
        ];

        $permissionsPermisos = [
            ['slug'=>'listar','name'=>'permiso-listar'],
            ['slug'=>'crear','name'=>'permiso-crear'],
            ['slug'=>'editar','name'=>'permiso-editar'],
            ['slug'=>'eliminar','name'=>'permiso-eliminar']
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'modulo_id' => 1,
            ]);
        }

        foreach ($permissionsUsers as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'modulo_id' => 2,
            ]);
        }

        foreach ($permissionsRoles as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'modulo_id' => 4,
            ]);
        }

        foreach ($permissionsPermisos as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'slug' => $permission['slug'],
                'modulo_id' => 5,
            ]);
        }
    }
}
