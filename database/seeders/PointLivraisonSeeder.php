<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointLivraisonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('point_livraisons')->insert([
            [
                'intitule' => 'Porto-Novo',
                'description' => 'La capitale',
                'statut' => true
            ],
            [
                'intitule' => 'Cotonou',
                'description' => 'La capitale éco',
                'statut' => true
            ],
            [
                'intitule' => 'Parakou',
                'description' => 'La ville facile',
                'statut' => true
            ],
            [
                'intitule' => 'Abomey-calavi',
                'description' => 'La ville des étudiants',
                'statut' => true
            ],
        ]);
    }
}
