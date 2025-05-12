<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'amount',
        'recipient_name',
        'message',
        'expires_at',
        'is_used'
    ];

    protected $dates = ['expires_at'];

    // Константы для ограничений
    public const CODE_LENGTH = 20;
    public const MIN_AMOUNT = 1000;
    public const MAX_AMOUNT = 1000000; // 1 млн рублей
    public const RECIPIENT_NAME_LENGTH = 100;
    public const MESSAGE_MAX_LENGTH = 500;
    public const DEFAULT_EXPIRATION_YEARS = 1;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function markAsUsed()
    {
        $this->update(['is_used' => true]);
    }
}