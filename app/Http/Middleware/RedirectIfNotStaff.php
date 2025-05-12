<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        // Если авторизован как пользователь - выходим
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        // Если не авторизован как сотрудник - редирект
        if (!Auth::guard('staff')->check()) {
            return redirect()->route('staff.login');
        }

        return $next($request);
    }
}