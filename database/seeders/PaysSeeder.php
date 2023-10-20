<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pays')->insert([
            [
                'nom' => 'Bénin',
                'statut' => true
            ],
            [
                'nom' => 'Togo',
                'statut' => true
            ]
        ]);
    }
}
