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
        Schema::create('point_livraisons', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
            $table->string('description')->nullable();
            $table->foreignId('pays_id');
            $table->boolean('statut')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_livraisons');
    }
};
