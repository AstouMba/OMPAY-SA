<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compte>
 */
class CompteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'numero_compte' => $this->faker->unique()->numerify('77########'),
            'type_compte' => $this->faker->randomElement(['epargne', 'courant', 'ompay']),
            'devise' => 'FCFA',
            'est_supprime' => false,
        ];
    }
}
