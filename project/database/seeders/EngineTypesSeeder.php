<?php

namespace Database\Seeders;

use App\Models\EngineType;
use Illuminate\Database\Seeder;

class EngineTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'Бензиновый'],
            ['name' => 'Дизельный'],
            ['name' => 'Электрический'],
            ['name' => 'Гибридный'],
        ];
        
        foreach ($types as $type) {
            EngineType::create($type);
        }
    }
}