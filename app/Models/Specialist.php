<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'service_id',
    ];

    /**
     * Get the service that owns the specialist.
     */
    public function staff()
    {
        return $this->hasMany(Staff::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the new appointments for the specialist.
     */
    public function newAppointments()
    {
        return $this->hasMany(NewAppointment::class);
    }
}
