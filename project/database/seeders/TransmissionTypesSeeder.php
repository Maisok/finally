<?php

namespace Database\Seeders;

use App\Models\TransmissionType;
use Illuminate\Database\Seeder;

class TransmissionTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'Механическая', 'Автоматическая', 'Роботизированная', 'Вариатор'
        ];
        
        foreach ($types as $type) {
            TransmissionType::create(['name' => $type]);
        }
    }
}