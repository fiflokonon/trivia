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
        $pays_cote_ivoire = Pays::where('nom', "Cote d'Ivoire")->first();
        DB::table('point_livraisons')->insert([
            [
                'intitule' => 'Abidjan, Centre cmmercial Angré Djibi',
                'description' => 'La capitale',
                'pays_id' => $pays_cote_ivoire->id,
                'statut' => true
            ],
            [
                'intitule' => 'Abidjan, Centre commercial Zone 3',
                'description' => 'La capitale éco',
                'pays_id' => $pays_cote_ivoire->id,
                'statut' => true
            ],
            [
                'intitule' => 'Parakou',
                'description' => 'La ville facile',
                'pays_id' => $pays_cote_ivoire->id,
                'statut' => true
            ],
            [
                'intitule' => 'Abidjan, Galerie du Parc Plateau',
                'description' => 'La Galerie',
                'pays_id' => $pays_cote_ivoire->id,
                'statut' => true
            ],
        ]);
    }
}
