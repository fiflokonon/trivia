<?php

namespace Database\Seeders;

use App\Models\Pays;
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
        $pays_benin = Pays::where('nom', 'Bénin')->first();
        DB::table('point_livraisons')->insert([
            [
                'intitule' => 'Porto-Novo',
                'description' => 'La capitale',
                'pays_id' => $pays_benin->id,
                'statut' => true
            ],
            [
                'intitule' => 'Cotonou',
                'description' => 'La capitale éco',
                'pays_id' => $pays_benin->id,
                'statut' => true
            ],
            [
                'intitule' => 'Parakou',
                'description' => 'La ville facile',
                'pays_id' => $pays_benin->id,
                'statut' => true
            ],
            [
                'intitule' => 'Abomey-calavi',
                'description' => 'La ville des étudiants',
                'pays_id' => $pays_benin->id,
                'statut' => true
            ],
        ]);
    }
}
