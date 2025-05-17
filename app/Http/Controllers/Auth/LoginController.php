<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        if (Auth::guard('staff')->check()) {
            Auth::guard('staff')->logout();
        }

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email:rfc,dns', 'max:100'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'email.required' => 'Поле email обязательно для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.max' => 'Email не должен превышать 100 символов',
            'password.required' => 'Поле пароль обязательно для заполнения',
            'password.max' => 'Пароль не должен превышать 255 символов',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard'));
            } else {
                return redirect()->route('verification.notice')
                    ->with('warning', 'Пожалуйста, подтвердите ваш email для полного доступа');
            }
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();


        return redirect('/');
    }
}