<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'address', 'phone', 'email', 'image',
        'work_time_start', 'work_time_end'
    ];

    protected $casts = [
        'work_time_start' => 'datetime:H:i',
        'work_time_end' => 'datetime:H:i',
    ];

    // Константы для ограничений
    public const MAX_NAME_LENGTH = 100;
    public const MAX_ADDRESS_LENGTH = 255;
    public const MAX_PHONE_LENGTH = 20;
    public const MAX_EMAIL_LENGTH = 100;
    public const MAX_IMAGE_PATH_LENGTH = 255;
    public const MAX_IMAGE_SIZE_KB = 2048; // 2MB
    public const MAX_IMAGE_WIDTH = 2000;
    public const MAX_IMAGE_HEIGHT = 2000;

    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function services()
    {
        return $this->hasManyThrough(Service::class, ServiceStaff::class);
    }
}