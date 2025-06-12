<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Equipment extends Model
{
    protected $fillable = [
        'name','generation_id', 'body_type_id', 'engine_type_id', 'engine_name', 
        'engine_volume', 'engine_power', 'transmission_type_id', 
        'transmission_name', 'drive_type_id', 'country_id', 'description',
        'weight', 'load_capacity', 'seats', 'fuel_consumption', 
        'fuel_tank_volume', 'battery_capacity', 'range', 'max_speed', 'clearance', 'model_path'
    ];

    protected $appends = ['model_url'];
    
    public function generation()
    {
        return $this->belongsTo(Generation::class);
    }
    
    public function bodyType()
    {
        return $this->belongsTo(BodyType::class);
    }
    
    public function engineType()
    {
        return $this->belongsTo(EngineType::class);
    }
    
    public function transmissionType()
    {
        return $this->belongsTo(TransmissionType::class);
    }
    
    public function driveType()
    {
        return $this->belongsTo(DriveType::class);
    }
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'equipment_colors');
    }
    
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function getModelUrlAttribute()
    {
        if (!$this->model_path) return null;
        
        return Storage::disk('public')->exists("{$this->model_path}/scene.gltf")
            ? Storage::disk('public')->url("{$this->model_path}/scene.gltf")
            : null;
    }

    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorite_equipments')
            ->withTimestamps();
    }

}