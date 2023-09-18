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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('email')->unique();
            $table->boolean('verified_email')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('indicatif');
            $table->string('phone')->unique();
            $table->string('photo_profil')->nullable();
            $table->date('date_naissance')->nullable();
            $table->foreignId('point_livraison_id')->nullable();
            $table->string('sexe')->nullable();
            $table->boolean('statut')->default(0);
            $table->boolean('admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
