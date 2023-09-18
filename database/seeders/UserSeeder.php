<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nom' => 'Aristofane LOKO',
                'email' => 'aristofanesmithloko@gmail.com',
                'indicatif' => '229',
                'phone' => '66006600',
                'photo_profil' => '',
                'date_naissance' => '',
                'sexe' => 'M',
                'point_livraison_id' => 2,
                'password' => 'password',
                'verified_email' => true,
                'admin' => true,
                'statut' => true
            ]
        ]);
    }
}
