<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceAnswer;

class ServiceAnswerPolicy
{
    public function update(User $user, ServiceAnswer $answer)
    {
        return $user->id === $answer->user_id;
    }
    
    public function delete(User $user, ServiceAnswer $answer)
    {
        return $user->id === $answer->user_id;
    }
}