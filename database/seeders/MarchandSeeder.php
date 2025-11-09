<?php

namespace Database\Seeders;

use App\Models\Marchand;
use Illuminate\Database\Seeder;

class MarchandSeeder extends Seeder
{
    public function run(): void
    {
        $marchands = [
            ['nom' => 'Woyofal', 'code_marchand' => 'WFL123'],
            ['nom' => 'Senelec', 'code_marchand' => 'SNL456'],
            ['nom' => 'Canal+', 'code_marchand' => 'CNL789'],
            ['nom' => 'Expresso Credit', 'code_marchand' => 'EXP321'],
        ];

        foreach ($marchands as $data) {
            Marchand::firstOrCreate($data);
        }
    }
}
