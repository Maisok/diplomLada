<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotUser
{
    public function handle(Request $request, Closure $next): Response
    {
        // Если авторизован как сотрудник - выходим
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }

        // Если не авторизован как пользователь - редирект
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}