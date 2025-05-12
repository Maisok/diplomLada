<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Проверяем, авторизован ли пользователь
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Проверяем роль пользователя
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Доступ запрещен');
        }

        return $next($request);
    }
}