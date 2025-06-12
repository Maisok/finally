<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentColor extends Model
{
    protected $fillable = ['equipment_id', 'color_id'];
    
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
}