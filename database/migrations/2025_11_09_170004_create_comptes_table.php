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
      Schema::create('comptes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('client_id')->constrained('clients')->onDelete('cascade');

            $table->string('numero_compte')->unique(); // Ex: 77xxxxxx 
            $table->enum('type_compte', ['epargne', 'courant', 'ompay'])->default('ompay');
            $table->string('devise')->default('FCFA');

            $table->boolean('est_supprime')->default(false); // Archive logique
            $table->timestamps();
        });

        // INDEX recommandés avec PostgreSQL
        Schema::table('comptes', function (Blueprint $table) {
            $table->index('client_id');
            $table->index('numero_compte');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comptes');
    }
};
