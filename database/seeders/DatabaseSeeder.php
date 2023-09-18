<?php

namespace Database\Seeders;
require __DIR__.'/functions.php' ;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
           UserSeeder::class,
           CommercantSeeder::class,
           PubliciteSeeder::class,
           ParametreSeeder::class,
           PointLivraisonSeeder::class
        ]);
    }
}
