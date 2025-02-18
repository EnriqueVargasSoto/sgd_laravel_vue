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
        $modulos = [
            //Seguridad
            ['nombre' => 'Seguridad',                   'slug' => 'seguridad',                  'ruta' => null,                         'descripcion' => 'agrupa todos los modulos pertenecientes a seguridad',         'padre_id'=> null, 'icono' => 'tabler-shield-lock',    'padre' => 1], //1
            ['nombre' => 'Modulos',                     'slug' => 'modulos',                    'ruta' => 'modulos',                    'descripcion' => 'acceso al curd de modulos',                                   'padre_id' => 1,   'icono' => null,                    'padre' => 0], //2
            ['nombre' => 'Usuarios',                    'slug' => 'usuarios',                   'ruta' => 'usuarios',                   'descripcion' => 'acceso al curd de usuarios',                                  'padre_id' => 1,   'icono' => null,                    'padre' => 0], //3
            ['nombre' => 'Roles y Permisos',            'slug' => 'roles-permisos',             'ruta' => null,                         'descripcion' => 'agrupa todos los modulos pertenecientes a roles y permisos',  'padre_id' => 1,   'icono' => null,                    'padre' => 1], //4
            ['nombre' => 'Roles',                       'slug' => 'roles',                      'ruta' => 'roles',                      'descripcion' => 'acceso al curd de roles',                                     'padre_id' => 4,   'icono' => null,                    'padre' => 0], //5
            ['nombre' => 'Permisos',                    'slug' => 'permisos',                   'ruta' => 'permisos',                   'descripcion' => 'acceso al curd de permisos',                                  'padre_id' => 4,   'icono' => null,                    'padre' => 0], //6
            //Maestros
            ['nombre' => 'Maestros',                    'slug' => 'maestros',                   'ruta' => null,                         'descripcion' => 'agrupa todos los modulos pertenecientes a maestros',          'padre_id'=> null, 'icono' => 'tabler-layout-kanban',  'padre' => 1], //7
            ['nombre' => 'Prioridades',                 'slug' => 'prioridades',                'ruta' => 'prioridades',                'descripcion' => 'acceso al curd de prioridades',                               'padre_id' => 7,   'icono' => null,                    'padre' => 0], //8
            ['nombre' => 'Tipo Unidad Organica',        'slug' => 'tipo-unidad-organica',       'ruta' => 'tipo-unidad-organica',       'descripcion' => 'acceso al curd de tipo unidad organica',                      'padre_id' => 7,   'icono' => null,                    'padre' => 0], //9
            ['nombre' => 'Cargos',                      'slug' => 'cargos',                     'ruta' => 'cargos',                     'descripcion' => 'acceso al curd de cargos',                                    'padre_id' => 7,   'icono' => null,                    'padre' => 0], //10
            ['nombre' => 'Unidad Organica',             'slug' => 'unidad-organica',            'ruta' => 'unidad-organica',            'descripcion' => 'acceso al curd de unidad organica',                           'padre_id' => 7,   'icono' => null,                    'padre' => 0], //11
            ['nombre' => 'Tipo Derivacion',             'slug' => 'tipo-derivacion',            'ruta' => 'tipo-derivacion',            'descripcion' => 'acceso al curd de tipo derivacion',                           'padre_id' => 7,   'icono' => null,                    'padre' => 0], //12
            ['nombre' => 'Tipo Dcomuento de Identidad', 'slug' => 'tipo-documento-identidad',   'ruta' => 'tipo-documento-identidad',   'descripcion' => 'acceso al curd de tipo de documentos de identidad',           'padre_id' => 7,   'icono' => null,                    'padre' => 0], //13
            ['nombre' => 'Tipo Dcomuento',              'slug' => 'tipo-documento',             'ruta' => 'tipo-documento',             'descripcion' => 'acceso al curd de tipo de docuemntos',                        'padre_id' => 7,   'icono' => null,                    'padre' => 0], //14

            //Gestion
            /* ['nombre' => 'Gestion',             'slug' => 'gestion',                'ruta' => null,                     'descripcion' => 'agrupa todos los modulos pertenecientes a gestion',          'modulo_id'=> null, 'icono' => 'tabler-layout-sidebar', 'padre' => 1], //13
            ['nombre' => 'Expedientes',         'slug' => 'expedientes',            'ruta' => 'expedientes',            'descripcion' => 'acceso al curd de expedientes',                               'modulo_id' => 13,  'icono' => null,                    'padre' => 0], //14
            ['nombre' => 'Derivaciones',        'slug' => 'derivaciones',           'ruta' => 'derivaciones',           'descripcion' => 'acceso al curd de derivaciones',                              'modulo_id' => 13,  'icono' => null,                    'padre' => 0], //15
            ['nombre' => 'Estados',             'slug' => 'estados',                'ruta' => 'estados',                'descripcion' => 'acceso al curd de estados',                                   'modulo_id' => 13,  'icono' => null,                    'padre' => 0], //16
            ['nombre' => 'Tipos de Documentos', 'slug' => 'tipos-documentos',       'ruta' => 'tipos-documentos',       'descripcion' => 'acceso al curd de tipos de documentos',                      'modulo_id' => 13,  'icono' => null,                    'padre' => 0], //17
            ['nombre' => 'Tipos de Expedientes', 'slug' => 'tipos-expedientes',     'ruta' => 'tipos-expedientes',     'descripcion' => 'acceso al curd de tipos de expedientes',                      'modulo_id' => 13,  'icono' => null,                    'padre' => 0], //18 */
        ];

        $permisos = [
            ['slug'=>'listar',  'name'=>'Listar'    ],
            ['slug'=>'crear',   'name'=>'Crear'     ],
            ['slug'=>'editar',  'name'=>'Editar'    ],
            ['slug'=>'eliminar','name'=>'Eliminar'  ]
        ];

        foreach ($modulos as $modulo) {
            $moduloNuevo = Modulo::create([
                'nombre'        => $modulo['nombre'],
                'slug'          => $modulo['slug'],
                'ruta'          => $modulo['ruta'],
                'descripcion'   => $modulo['descripcion'],
                'padre_id'     => $modulo['padre_id'],
                'icono'         => $modulo['icono'],
                'padre'         => $modulo['padre'],
            ]);

            foreach ($permisos as $key => $permiso) {
                # code...
                Permission::create([
                    'name'      => $permiso['name'].' '.$moduloNuevo->nombre,
                    'slug'      => $permiso['slug'],
                    'modulo_id' => $moduloNuevo->id,
                ]);
            }
        }

    }
}
