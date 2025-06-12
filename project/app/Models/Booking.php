<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'car_id', 'booking_date', 'appointment_date', 
        'status', 'manager_comment', 'manager_id'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'appointment_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }
}