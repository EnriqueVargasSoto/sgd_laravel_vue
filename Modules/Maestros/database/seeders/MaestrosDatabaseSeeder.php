<?php

namespace Modules\Maestros\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Maestros\Models\TipoDerivacion;
use Modules\Maestros\Models\TipoDocumentoIdentidad;

class MaestrosDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $this->call(TipoDerivacion::class);
        $this->call(TipoDocumentoIdentidad::class);
        $this->call(TipoUnidadOrganicaSeeder::class);
        $this->call(UnidadOrganicaSeeder::class);
    }
}
