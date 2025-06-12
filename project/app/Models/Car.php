<?php

namespace App\Models;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'equipment_id', 'vin', 'mileage', 'price', 'description', 'is_sold', 'branch_id',
        'color_id', 'custom_color_name', 'custom_color_hex'
    ];
    
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    protected $appends = ['is_booked_by_me'];

    public function getIsBookedByMeAttribute()
    {
        return $this->bookings()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();
    }
    
    public function images()
    {
        return $this->hasMany(CarImage::class);
    }
    
    public function getMainImageAttribute()
    {
        return $this->images->where('is_main', true)->first() ?? $this->images->first();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function activeBooking()
    {
        return $this->hasOne(Booking::class)
            ->whereIn('status', ['pending', 'confirmed']);
    }

    public function getIsBookedAttribute()
    {
        return $this->activeBooking()->exists();
    }
    
    public function getFullNameAttribute()
    {
        return $this->equipment->generation->carModel->brand->name . ' ' . 
            $this->equipment->generation->carModel->name . ' ' . 
            $this->equipment->generation->name;
    }
}