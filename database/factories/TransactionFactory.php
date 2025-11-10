<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'compte_id' => \App\Models\Compte::factory(),
            'marchand_id' => \App\Models\Marchand::factory(),
            'telephone_marchand' => $this->faker->optional()->numerify('77########'),
            'type' => $this->faker->randomElement([
                'depot',
                'retrait',
                'transfert_debit',
                'transfert_credit',
                'paiement_marchand'
            ]),
            'montant' => $this->faker->numberBetween(1000, 500000),
            'statut' => $this->faker->randomElement(['en_attente', 'validee', 'annulee']),
        ];
    }
}
