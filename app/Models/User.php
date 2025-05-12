<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Исправленный импорт
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'yandex_id',
        'email_verified_at',
    ];

    /**
     * Get the appointments for the user.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the new appointments for the user.
     */
    public function newAppointments()
    {
        return $this->hasMany(NewAppointment::class);
    }

    public function giftCertificates()
    {
        return $this->hasMany(GiftCertificate::class);
    }

    public function unreadQuestionNotifications()
    {
        return $this->hasMany(QuestionNotification::class, 'user_id')
                    ->where('is_read', false)
                    ->with('question.service'); // для оптимизации запросов
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}