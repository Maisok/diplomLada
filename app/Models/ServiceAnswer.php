<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceAnswer extends Model
{
    protected $fillable = ['question_id', 'user_id', 'answer'];
    
    public function question()
    {
        return $this->belongsTo(ServiceQuestion::class, 'question_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}