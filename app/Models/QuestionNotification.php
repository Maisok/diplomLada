<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionNotification extends Model
{
    protected $fillable = ['user_id', 'question_id', 'is_read'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function question()
    {
        return $this->belongsTo(ServiceQuestion::class);
    }
}