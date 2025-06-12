<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            CountriesSeeder::class,
            BodyTypesSeeder::class,
            EngineTypesSeeder::class,
            TransmissionTypesSeeder::class,
            DriveTypesSeeder::class,
        ]);
    }
}