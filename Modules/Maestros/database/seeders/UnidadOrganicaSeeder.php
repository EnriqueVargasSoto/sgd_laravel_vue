<?php

namespace Modules\Maestros\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Maestros\Models\UnidadOrganica;

class UnidadOrganicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $unidades_organicas = [
            ['tipo_unidad_organica_id' => 4, 'padre_id' => null, 'tipo_derivacion_id' => 1, 'nombre' => 'Oficina de Estadística e Informática']//1
        ];

        foreach ($unidades_organicas as $key => $unidad_organica) {
            UnidadOrganica::create([
                'tipo_unidad_organica_id' => $unidad_organica['tipo_unidad_organica_id'],
                'padre_id' => $unidad_organica['padre_id'],
                'tipo_derivacion_id' => $unidad_organica['tipo_derivacion_id'],
                'nombre' => $unidad_organica['nombre']
            ]);
        }
    }
}
