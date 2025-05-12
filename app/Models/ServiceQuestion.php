<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceQuestion extends Model
{
    protected $fillable = ['service_id', 'user_id', 'question', 'is_closed'];
    
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function answers()
    {
        return $this->hasMany(ServiceAnswer::class, 'question_id');
    }
    
    public function notifications()
    {
        return $this->hasMany(QuestionNotification::class, 'question_id');
    }

    public function markAsRead()
    {
        $this->notifications()->update(['is_read' => true]);
    }
}