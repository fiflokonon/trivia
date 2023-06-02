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
        Schema::create('paniers', function (Blueprint $table) {
            $table->id();
            $table->json('produits');
            $table->string('numero_panier')->unique();
            $table->bigInteger('sous_total');
            $table->bigInteger('frais_fournisseur')->default(0);
            $table->bigInteger('frais_livraison')->default(0);
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('statut_paiement')->default(0);
            $table->string('id_transaction')->nullable();
            $table->string('lien_qr_code')->nullable();
            $table->boolean('statut');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paniers');
    }
};
