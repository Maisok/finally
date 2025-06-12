<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    protected $fillable = ['car_model_id', 'name', 'year_from', 'year_to'];
    
    public function carModel()
    {
        return $this->belongsTo(CarModel::class);
    }
    
    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}