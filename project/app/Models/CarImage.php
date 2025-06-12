<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarImage extends Model
{
    protected $fillable = ['car_id', 'path', 'is_main'];
    
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}