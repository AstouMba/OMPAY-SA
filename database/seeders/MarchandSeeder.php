<?php

namespace Database\Seeders;

use App\Models\Marchand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarchandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marchandsData = [
            [
                'nom' => 'Magasin Mama Fall',
                'telephone' => '+221700000001',
                'code_marchand' => 'M123456',
            ],
            [
                'nom' => 'Épicerie Diamono',
                'telephone' => '+221700000002',
                'code_marchand' => 'M123457',
            ],
            [
                'nom' => 'Librairie Keur Serigne Touba',
                'telephone' => '+221700000003',
                'code_marchand' => 'M123458',
            ],
            [
                'nom' => 'Restaurant Le Djolof',
                'telephone' => '+221700000004',
                'code_marchand' => 'M123459',
            ],
            [
                'nom' => 'Pharmacie Gandhi',
                'telephone' => '+221700000005',
                'code_marchand' => 'M123460',
            ],
        ];

        foreach ($marchandsData as $marchandData) {
            Marchand::firstOrCreate(
                ['telephone' => $marchandData['telephone']],
                [
                    'id' => Str::uuid(),
                    'nom' => $marchandData['nom'],
                    'telephone' => $marchandData['telephone'],
                    'code_marchand' => $marchandData['code_marchand'],
                ]
            );
        }
    }
}
