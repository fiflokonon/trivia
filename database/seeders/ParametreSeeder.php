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
                'description' => 'Frais Trivia sur un premier article',
                'valeur' => 14,
                'statut' => true
            ],
            [
                'nom' => 'frais_trivia_article_suivant',
                'description' => 'Frais Trivia à partir du deuxième article',
                'valeur' => 5,
                'statut' => true
            ],
            [
                'nom' => 'euro_value',
                'description' => "La valeur de l'Euro sur Trivia",
                'valeur' => 657,
                'statut' => true
            ]
        ]);
    }
}
