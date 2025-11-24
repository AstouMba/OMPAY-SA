<?php

namespace Database\Seeders;

use App\Models\Marchand;
use Illuminate\Database\Seeder;

class MarchandSeeder extends Seeder
{
    public function run(): void
    {
        $marchands = [
            ['nom' => 'Woyofal', 'telephone' => '771234567'],
            ['nom' => 'Senelec', 'telephone' => '772345678'],
            ['nom' => 'Canal+', 'telephone' => '773456789'],
            ['nom' => 'Expresso Credit', 'telephone' => '774567890'],
            ['nom' => 'Test Marchand', 'telephone' => '775678901'],
        ];

        foreach ($marchands as $data) {
            $data['code_marchand'] = Marchand::genererCodeMarchand();
            Marchand::firstOrCreate(
                ['nom' => $data['nom'], 'telephone' => $data['telephone']],
                $data
            );
        }
    }
}
