<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmailChange;
use Illuminate\Support\Str;
use App\Models\Branch;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Zа-яА-Я\s]+$/u'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:100',
                'unique:users,email,' . $user->id,
                function ($attribute, $value, $fail) use ($user) {
                    // Проверка уникальности среди филиалов
                    if (Branch::where('email', $value)->exists()) {
                        $fail('Этот email уже используется в системе');
                    }
                    
                    // Проверка на изменение email
                    if ($value !== $user->email && $user->new_email === $value) {
                        $fail('На этот email уже отправлено письмо с подтверждением');
                    }
                }
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ], [
            'name.required' => 'Имя обязательно для заполнения',
            'name.max' => 'Имя не должно превышать 50 символов',
            'name.regex' => 'Имя может содержать только буквы и пробелы',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.max' => 'Email не должен превышать 100 символов',
            'email.unique' => 'Этот email уже используется',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
            'password.max' => 'Пароль не должен превышать 255 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, одну строчную букву и одну цифру',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Обновление имени
        $user->name = $request->name;
        
        // Обновление пароля, если введен
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Если email изменился
        if ($user->email !== $request->email) {
            // Генерируем токен подтверждения
            $token = Str::random(60);
            
            // Сохраняем новый email и токен во временные поля
            $user->new_email = $request->email;
            $user->email_verify_token = $token;
            
            // Отправляем письмо подтверждения
            Mail::to($request->email)->send(new VerifyEmailChange($token, $user));
            
            // Сообщение пользователю
            $request->session()->flash('email_change', [
                'message' => 'На новый email отправлено письмо с подтверждением',
                'old_email' => $user->email,
                'new_email' => $request->email
            ]);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Профиль успешно обновлен!');
    }

    public function verifyEmailChange($token)
    {
        $user = Auth::user();
        
        if ($user->email_verify_token === $token) {
            // Проверяем, не используется ли email в филиалах
            if (Branch::where('email', $user->new_email)->exists()) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Этот email уже используется в системе');
            }

            // Обновляем email
            $user->email = $user->new_email;
            $user->new_email = null;
            $user->email_verify_token = null;
            $user->email_verified_at = now();
            $user->save();
            
            return redirect()->route('profile.edit')
                ->with('success', 'Email успешно подтвержден и изменен!');
        }
        
        return redirect()->route('profile.edit')
            ->with('error', 'Неверная ссылка подтверждения');
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = Auth::user();
        
        if ($user->new_email) {
            // Проверяем, не используется ли email в филиалах
            if (Branch::where('email', $user->new_email)->exists()) {
                return back()->with('error', 'Этот email уже используется в системе');
            }

            Mail::to($user->new_email)->send(new VerifyEmailChange($user->email_verify_token, $user));
            
            return back()->with('email_change', [
                'message' => 'Письмо с подтверждением отправлено повторно',
                'old_email' => $user->email,
                'new_email' => $user->new_email
            ]);
        }
        
        return back();
    }

    public function destroy(Request $request)
    {
        \Log::info('Delete account request received', ['user_id' => $request->user()->id]);
        $user = $request->user();

        // Если пользователь вошел через Яндекс, пропускаем проверку пароля
        if (empty($user->yandex_id)) {
            $validator = Validator::make($request->all(), [
                'password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail('Неверный пароль');
                        }
                    }
                ],
            ], [
                'password.required' => 'Для удаления аккаунта требуется пароль'
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }
        }

        // Удаляем связанные данные
        $user->newAppointments()->delete();
        $user->giftCertificates()->delete();

        Auth::logout();

        if ($user->delete()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect('/')->with('status', 'Ваш аккаунт был успешно удален.');
        }

        return back()->withErrors(['error' => 'Не удалось удалить аккаунт. Пожалуйста, попробуйте позже.']);
    }
}