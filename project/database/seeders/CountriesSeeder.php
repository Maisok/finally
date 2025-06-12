<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['name' => 'Россия'],
            ['name' => 'Германия'],
            ['name' => 'Япония'],
            ['name' => 'США'],
            ['name' => 'Китай'],
            ['name' => 'Корея'],
            ['name' => 'Франция'],
            ['name' => 'Италия'],
            ['name' => 'Великобритания'],
            ['name' => 'Швеция'],
        ];
        
        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}