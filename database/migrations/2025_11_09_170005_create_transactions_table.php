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
      Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('compte_id')->constrained('comptes')->onDelete('cascade');
            $table->foreignUuid('marchand_id')->nullable()->constrained('marchands')->nullOnDelete();
            $table->string('telephone_marchand')->nullable();


            $table->enum('type', [
                'depot', 
                'retrait',
                'transfert_debit', 
                'transfert_credit', 
                'paiement_marchand'
            ]);

            $table->bigInteger('montant'); // montant en FCFA
            $table->enum('statut', ['en_attente', 'validee', 'annulee'])->default('validee');

            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('compte_id');
            $table->index(['type', 'statut']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
