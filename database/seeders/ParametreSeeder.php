<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ParametreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('parametres')->insert([
            [
                'nom' => 'frais_trivia_premier_article',
                'valeur' => 20,
                'statut' => true
            ],
            [
                'nom' => 'frais_trivia_article_suivant',
                'valeur' => 5,
                'statut' => true
            ]
        ]);
    }
}
