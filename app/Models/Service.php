<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'image',
        'price',
    ];
    
    // Максимальные длины полей для валидации
    public const MAX_NAME_LENGTH = 50;
    public const MAX_IMAGE_PATH_LENGTH = 255;
    public const MAX_PRICE_VALUE = 99999999.99;
    
    public function staff()
    {
        return $this->belongsToMany(Staff::class);
    }

    public function newAppointments()
    {
        return $this->hasMany(NewAppointment::class);
    }

    public function hasUnreadQuestions($userId)
    {
        return $this->questions()
            ->where('user_id', $userId)
            ->whereHas('notifications', function($query) {
                $query->where('is_read', false);
            })
            ->exists();
    }

    public function questions()
    {
        return $this->hasMany(ServiceQuestion::class);
    }
}