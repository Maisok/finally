<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriveType extends Model
{
    protected $fillable = ['name'];
    
    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}