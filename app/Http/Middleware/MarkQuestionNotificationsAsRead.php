<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MarkQuestionNotificationsAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   // app/Http/Middleware/MarkQuestionNotificationsAsRead.php
    public function handle($request, Closure $next)
    {
        if ($request->route('question') && auth()->check()) {
            $question = $request->route('question');
            if ($question->user_id === auth()->id()) {
                $question->markAsRead();
            }
        }
        
        return $next($request);
    }
}
