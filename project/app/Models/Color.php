<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'hex_code'];
    
    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'equipment_colors');
    }
}