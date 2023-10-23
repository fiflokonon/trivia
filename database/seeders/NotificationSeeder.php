<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notifications')->insert([
            [
                'titre' => 'Changement de statut',
                'contenu' => 'Votre commande a changé de statut',
                'vu' => false,
                'user_id' => 2,
                'statut' => true
            ] 
            ]);
    }
}
