<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crée un administrateur de test si n'existe pas
        $email = env('ADMIN_TEST_EMAIL', 'admin@example.com');
        $user = User::where('email', $email)->first();

        if (! $user) {
            User::create([
                'name' => env('ADMIN_TEST_NAME', 'Admin Test'),
                'email' => $email,
                'password' => Hash::make(env('ADMIN_TEST_PASSWORD', 'secret123')),
                'role' => 'admin',
            ]);
        }
    }
}
