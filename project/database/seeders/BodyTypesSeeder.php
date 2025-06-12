<?php

namespace Database\Seeders;

use App\Models\BodyType;
use Illuminate\Database\Seeder;

class BodyTypesSeeder extends Seeder
{
    public function run()
    {
        $types = [
            'Седан', 'Хэтчбек', 'Универсал', 'Купе', 'Кабриолет', 
            'Внедорожник', 'Кроссовер', 'Минивэн', 'Пикап', 'Фургон'
        ];
        
        foreach ($types as $type) {
            BodyType::create(['name' => $type]);
        }
    }
}