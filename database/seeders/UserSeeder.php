<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nom' => 'Support Trivia',
                'email' => 'admin@triviaprive.com',
                'indicatif' => '229',
                'phone' => '66006600',
                'photo_profil' => '',
                'sexe' => 'M',
                'point_livraison_id' => 2,
                'password' => Hash::make('password'),
                'verified_email' => true,
                'admin' => true,
                'statut' => true
            ]
        ]);
    }
}
