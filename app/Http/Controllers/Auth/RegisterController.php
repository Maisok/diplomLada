<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Проверка reCAPTCHA (только для продакшена)
        if (!app()->environment('local')) {
            $recaptchaResponse = $this->verifyRecaptcha($request);
            if (!$recaptchaResponse['success']) {
                return redirect()->back()
                    ->withErrors(['g-recaptcha-response' => 'Ошибка проверки reCAPTCHA'])
                    ->withInput();
            }
        }

        // Проверка уникальности email среди пользователей и филиалов
        if ($this->emailExists($request->email)) {
            return redirect()->back()
                ->withErrors(['email' => 'Этот email уже используется'])
                ->withInput();
        }

        $user = $this->create($request->all());

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );
        
        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        Auth::login($user);

        return redirect()->route('verification.notice')
            ->with('message', 'На вашу почту отправлено письмо с подтверждением.');
    }

    protected function emailExists($email)
    {
        return User::where('email', $email)->exists() || 
               Branch::where('email', $email)->exists();
    }

    protected function verifyRecaptcha(Request $request)
    {
        $client = new Client([
            'verify' => false,
        ]);

        try {
            $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => [
                    'secret' => config('services.recaptcha.secret'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ],
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (\Exception $e) {
            return ['success' => false];
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-Zа-яА-Я\s\-]+$/u'
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:100',
                function ($attribute, $value, $fail) {
                    if (User::where('email', $value)->exists() || 
                        Branch::where('email', $value)->exists()) {
                        $fail('Этот email уже используется');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
            'g-recaptcha-response' => [
                app()->environment('local') ? 'nullable' : 'required'
            ],
        ], [
            'name.required' => 'Имя обязательно для заполнения',
            'name.max' => 'Имя не должно превышать 50 символов',
            'name.regex' => 'Имя может содержать только буквы, пробелы и дефисы',
            'email.required' => 'Email обязателен для заполнения',
            'email.email' => 'Введите корректный email адрес',
            'email.max' => 'Email не должен превышать 100 символов',
            'password.required' => 'Пароль обязателен для заполнения',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
            'password.max' => 'Пароль не должен превышать 255 символов',
            'password.confirmed' => 'Пароли не совпадают',
            'password.regex' => 'Пароль должен содержать хотя бы одну заглавную букву, одну строчную букву и одну цифру',
            'g-recaptcha-response.required' => 'Подтвердите, что вы не робот',
        ]);
    }

    protected function create(array $data)
    {
        // Удаляем старые неподтвержденные записи с этим email
        User::where('email', $data['email'])
            ->whereNull('email_verified_at')
            ->delete();

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => null,
            'role' => 'user'
        ]);
    }

    public function yandex()
    {
        return Socialite::driver('yandex')->redirect();
    }
   
    public function yandexRedirect()
    {
        try {
            $socialite = Socialite::driver('yandex');
            $socialite->setHttpClient(new \GuzzleHttp\Client([
                'verify' => false,
                'timeout' => 30,
            ]));

            $yandexUser = $socialite->user();

            // Проверяем, не используется ли email в филиалах
            if (Branch::where('email', $yandexUser->email)->exists()) {
                return redirect()->route('login')
                    ->withErrors(['email' => 'Этот email уже используется в системе']);
            }

            $user = User::where('email', $yandexUser->email)
                ->orWhere('yandex_id', $yandexUser->id)
                ->first();

            if (!$user) {
                $user = User::create([
                    'email' => $yandexUser->email ?? $yandexUser->id.'@yandex.temp',
                    'name' => $yandexUser->name ?? 'Пользователь',
                    'yandex_id' => $yandexUser->id,
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'user'
                ]);
            } else {
                $user->update([
                    'yandex_id' => $yandexUser->id,
                    'email_verified_at' => now()
                ]);
            }

            Auth::login($user, true);
            return redirect()->route('main');

        } catch (\Exception $e) {
            \Log::error('Yandex auth error: '.$e->getMessage());
            return redirect()->route('login')
                   ->withErrors('Ошибка авторизации через Яндекс');
        }
    }
}