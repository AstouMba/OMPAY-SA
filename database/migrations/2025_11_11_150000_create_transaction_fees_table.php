<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_fees', function (Blueprint $table) {
            $table->id();
            $table->string('type_transaction'); // 'transfert', 'paiement_marchand', etc.
            $table->decimal('pourcentage_frais', 5, 4)->default(0); // ex: 0.008 pour 0.8%
            $table->decimal('frais_fixe', 10, 2)->default(0); // frais fixe optionnel
            $table->boolean('actif')->default(true);
            $table->timestamps();

            $table->index(['type_transaction', 'actif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_fees');
    }
};