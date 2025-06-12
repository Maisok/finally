<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Notifications\DatabaseNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function isSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function favoriteEquipments()
    {
        return $this->belongsToMany(Equipment::class, 'favorite_equipments')
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function removeFromFavorites($equipmentId)
    {
        return $this->favoriteEquipments()->detach($equipmentId);
    }
    
    public function clearFavorites()
    {
        return $this->favoriteEquipments()->detach();
    }
}