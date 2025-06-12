<?php

namespace Database\Seeders;

use App\Models\DriveType;
use Illuminate\Database\Seeder;

class DriveTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'Передний', 'Задний', 'Полный'
        ];
        
        foreach ($types as $type) {
            DriveType::create(['name' => $type]);
        }
    }
}