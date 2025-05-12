<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Находим пользователя
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                       ->withErrors(['email' => __('Пользователь с таким email не найден')]);
        }

        // Генерируем токен
        $token = Password::createToken($user);

        // Формируем URL для сброса
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));

        // Отправляем письмо напрямую
        Mail::to($user->email)->send(new ResetPassword($url));

        return back()->with('status', __('Ссылка для сброса пароля отправлена на ваш email'));
    }
}