<?php

namespace Modules\Maestros\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Maestros\Models\TipoDerivacion;

class TipoDerivacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);

        $tipos_derivacion = [
            ['derivacion' => 'Derivación Lineal',   'slug' => 'DL', 'niveles' => '1', 'estado' => 1],
            ['derivacion' => 'Derivación Directa',  'slug' => 'DD', 'niveles' => 'n', 'estado' => 1],
        ];

        foreach ($tipos_derivacion as $key => $tipos) {
            TipoDerivacion::create([
                'derivacion' => $tipos['derivacion'],
                'slug' => $tipos['slug'],
                'niveles' => $tipos['niveles'],
                'estado' => $tipos['estado']
            ]);
        }
    }
}
