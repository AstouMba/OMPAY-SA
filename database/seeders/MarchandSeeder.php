<?php

namespace Database\Seeders;

use App\Models\Marchand;
use Illuminate\Database\Seeder;

class MarchandSeeder extends Seeder
{
    public function run(): void
    {
        $marchands = [
            ['nom' => 'Woyofal', 'code_marchand' => 'WFL123', 'telephone' => '771234567'],
            ['nom' => 'Senelec', 'code_marchand' => 'SNL456', 'telephone' => '772345678'],
            ['nom' => 'Canal+', 'code_marchand' => 'CNL789', 'telephone' => '773456789'],
            ['nom' => 'Expresso Credit', 'code_marchand' => 'EXP321', 'telephone' => '774567890'],
            ['nom' => 'Test Marchand', 'code_marchand' => 'M123456', 'telephone' => '775678901'],
        ];

        foreach ($marchands as $data) {
            Marchand::firstOrCreate($data);
        }
    }
}
