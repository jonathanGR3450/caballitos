<?php

namespace Database\Seeders;

use App\Models\TipoListado;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TipoListadoSeeder extends Seeder
{
    public function run(): void
    {
        $planes = [
            [
                'nombre'           => 'Regular',
                'slug'             => 'regular',
                'descripcion'      => 'Plan gratuito básico',
                'max_productos'    => 2,
                'is_ilimitado'     => false,
                'periodo'          => 'month',
                'periodo_cantidad' => 1,
                'precio'       => 0,
                'is_activo'        => true,
            ],
            [
                'nombre'           => 'Destacado',
                'slug'             => 'destacado',
                'descripcion'      => 'Visibilidad mejorada',
                'max_productos'    => 5,
                'is_ilimitado'     => false,
                'periodo'          => 'month',
                'periodo_cantidad' => 1,
                'precio'       => 200,
                'is_activo'        => true,
            ],
            [
                'nombre'           => 'Premium',
                'slug'             => 'premium',
                'descripcion'      => 'Máxima exposición',
                'max_productos'    => 10,
                'is_ilimitado'     => false,
                'periodo'          => 'month',
                'periodo_cantidad' => 1,
                'precio'       => 250,
                'is_activo'        => true,
            ],
            [
                'nombre'           => 'Granjas de cría',
                'slug'             => 'granjas-de-cria',
                'descripcion'      => 'Publicaciones ilimitadas por mes',
                'max_productos'    => null,
                'is_ilimitado'     => true,
                'periodo'          => 'month',
                'periodo_cantidad' => 1,
                'precio'       => 300,
                'is_activo'        => true,
            ],
        ];

        foreach ($planes as $p) {
            TipoListado::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }
    }
}
