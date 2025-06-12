<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransmissionType extends Model
{
    protected $fillable = ['name'];
    
    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}