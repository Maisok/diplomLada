<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceQuestion;

class ServiceQuestionPolicy
{
    public function update(User $user, ServiceQuestion $question)
    {
        return $user->id === $question->user_id;
    }
    
    public function delete(User $user, ServiceQuestion $question)
    {
        return $user->id === $question->user_id;
    }
    
    public function close(User $user, ServiceQuestion $question)
    {
        return $user->id === $question->user_id && $question->answers()->exists();
    }
}