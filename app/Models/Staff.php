<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'position',
        'image',
        'branch_id',
        'login',
        'password',
        'is_active' // добавлено
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}