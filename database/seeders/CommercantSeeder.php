<?php

namespace Database\Seeders;

use App\Models\Commercant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommercantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $commercants = [
            [
                'nom' => 'Shein',
                'montant_min' => 0.00,
                'montant_max' => 39.00,
                'frais' => 3.90,
                'statut' => true
            ],
            [
                'nom' => 'Footlocker',
                'montant_min' => 0.00,
                'montant_max' => 99.00,
                'frais' => 3.90,
                'statut' => true
            ],
            [
                'nom' => 'Zara',
                'montant_min' => 0.00,
                'montant_max' => 50.00,
                'frais' => 3.95,
                'statut' => true
            ],
            [
                'nom' => 'PrettyLittleThing',
                'montant_min' => 0.00,
                'montant_max' => 0.00,
                'frais' => 5.99,
                'statut' => true
            ],
            [
                'nom' => 'Zalando',
                'montant_min' => 0.00,
                'montant_max' => 34.00,
                'frais' => 4.95,
                'statut' => true
            ],
            [
                'nom' => 'Asos',
                'montant_min' => 0.00,
                'montant_max' => 45.00,
                'frais' => 4.45,
                'statut' => true
            ],
        ];

        foreach ($commercants as $commercantData) {
            Commercant::firstOrCreate($commercantData);
        }
    }
}
