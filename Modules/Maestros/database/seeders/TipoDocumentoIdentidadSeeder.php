<?php

namespace Modules\Maestros\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Maestros\Models\TipoDocumentoIdentidad;

class TipoDocumentoIdentidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->call([]);
        $tipos_documento_identidad = [
            ['tipo' => 'Documento Nacional de Identidad',   'slug' => 'DNI',    'estado' =>1],//1
            ['tipo' => 'Carnet de Extranjería',             'slug' => 'CE',     'estado' =>1],//2
            ['tipo' => 'Pasaporte',                         'slug' => 'PAS',    'estado' =>1],//3
            ['tipo' => 'Partida de Nacimiento',             'slug' => 'PN',     'estado' =>1],//4
            ['tipo' => 'Carnet de Identidad',               'slug' => 'CI',     'estado' =>1],//5
            ['tipo' => 'Otro',                              'slug' => 'OTRO',   'estado' =>1],//6
        ];

        foreach ($tipos_documento_identidad as $key => $tipo_documento_identidad) {
            TipoDocumentoIdentidad::create([
                'tipo' => $tipo_documento_identidad['tipo'],
                'slug' => $tipo_documento_identidad['slug'],
                'estado' => $tipo_documento_identidad['estado']
            ]);
        }
    }
}
