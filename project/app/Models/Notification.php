<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'car_id', 'type', 'message', 'read_at', 'url'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }
}