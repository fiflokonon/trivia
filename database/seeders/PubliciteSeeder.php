<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PubliciteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @throws \Exception
     */
    public function run(): void
    {
        DB::table('publicites')->insert([
            [
                'image' => getPubliciteImageName('slide.png'),
                'description' => 'Publicités',
                'statut' => true
            ]
        ]);
    }
}
