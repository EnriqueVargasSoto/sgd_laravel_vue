<?php

namespace Modules\Seguridad\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\Seguridad\Models\Persona;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        $persona = Persona::create([
            'unidad_organica_id' => 1,
            'nombres' => 'Enrique Segundo',
            'apellidos' => 'Vargas Soto',
            'tipo_documento_identidad_id' => 1,
            'numero_documento' => '12345678',
            'edad' => 29,
            'telefono' => '123456789',
            'direccion' => 'Jr. Los Pinos 123'
        ]);

        $user = User::create([
            'persona_id' => $persona->id,
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $role = Role::create(['name' => 'Admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
