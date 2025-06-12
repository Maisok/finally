<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'address', 'image', 'latitude', 'longitude'];

    public function cars()
    {
        return $this->hasMany(Car::class);
    }
}