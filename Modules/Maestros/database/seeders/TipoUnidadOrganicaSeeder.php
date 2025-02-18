<?php

namespace Modules\Maestros\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Maestros\Models\TipoUnidadOrganica;

class TipoUnidadOrganicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $tipos_unidad_organica = [
            ['nombre' => 'Dirección Genereal',          'slug' => 'DG'],//1
            ['nombre' => 'Dirección Administrativa',    'slug' => 'DA'],//2
            ['nombre' => 'Departamento'                 ,'slug' => 'DPTO'],//3
            ['nombre' => 'Oficina'                      ,'slug' => 'OF'],//4
            ['nombre' => 'Servicio'                     ,'slug' => 'SV'],//5
            ['nombre' => 'Unidad'                       ,'slug' => 'UN'],//6
            ['nombre' => 'Equipo de Trabajo'            ,'slug' => 'ET'],//7
            ['nombre' => 'TAP'                          ,'slug' => 'TAP'],//8
            ['nombre' => 'Comité'                       ,'slug' => 'COMT'],//9
            ['nombre' => 'Secretaría'                   ,'slug' => 'SEC'],//10
        ];

        foreach ($tipos_unidad_organica as $key => $tipos) {
            TipoUnidadOrganica::create([
                'nombre'    => $tipos['nombre'],
                'slug'      => $tipos['slug'],
            ]);
        }
    }
}
