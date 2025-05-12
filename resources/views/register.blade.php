<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Регистрация</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background-color: #faf9f7;
        }
        h1, h2, h3 {
            font-family: 'Playfair Display', serif;
        }
        .hero-overlay {
            background: linear-gradient(90deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 100%);
        }
        .form-container {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        .form-input {
            border-bottom: 1px solid #ddd;
            background: transparent;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-bottom-color: #8b5f4d;
        }
        .btn-primary {
            background-color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #6d4a3a;
        }
        .nav-link {
            position: relative;
        }
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: 0;
            left: 0;
            background-color: #8b5f4d;
            transition: width 0.3s ease;
        }
        .nav-link:hover:after {
            width: 100%;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[400px] flex items-center justify-center">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Фон регистрации" class="absolute top-0 left-0 w-full h-full object-cover">
        <div class="relative z-20 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-6">Присоединяйтесь к нам</h1>
            <a href="#registration" class="btn-primary text-white px-8 py-3 rounded-full font-medium inline-block">
                Зарегистрироваться
            </a>
        </div>
    </section>

    <!-- Registration Form -->
    <section id="registration" class="py-16">
        <div class="max-w-md mx-auto px-4">
            <div class="form-container bg-white rounded-xl p-8 md:p-12">
                <h2 class="text-2xl font-bold mb-8 text-center">Создать аккаунт</h2>
                
                <form action="{{ route('register') }}" method="POST" class="space-y-6" onsubmit="return validateForm()">
                    @csrf
                    
                    <!-- Name Input -->
                    <div>
                        <label class="block text-sm font-medium mb-2" for="name">Имя</label>
                        <input type="text" id="name" name="name" placeholder="Ваше имя" 
                               class="form-input w-full px-0 py-2 focus:outline-none">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-medium mb-2" for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Ваш email" 
                               class="form-input w-full px-0 py-2 focus:outline-none">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Input -->
                    <div>
                        <label class="block text-sm font-medium mb-2" for="password">Пароль</label>
                        <input type="password" id="password" name="password" minlength="8" placeholder="Не менее 8 символов" 
                               class="form-input w-full px-0 py-2 focus:outline-none">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password Input -->
                    <div>
                        <label class="block text-sm font-medium mb-2" for="password_confirmation">Подтвердите пароль</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" placeholder="Повторите пароль" 
                               class="form-input w-full px-0 py-2 focus:outline-none">
                    </div>
                    
                    <!-- reCAPTCHA -->
                    <div class="g-recaptcha" data-sitekey="6LeMgYcqAAAAACoW577UUV0cHEvMKsgQZWwECdTL"></div>
                    @error('g-recaptcha-response')
                        <p class="text-red-500 text-xs mt-1">Пожалуйста, подтвердите, что вы не робот.</p>
                    @enderror
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full text-white py-3 rounded-full font-medium mt-6">
                        Зарегистрироваться
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{route('login')}}" class="text-sm text-[#8b5f4d] hover:underline">Уже есть аккаунт? Войти</a>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600 mb-4">Или войдите через</p>
                        <a href="{{ route('auth.yandex') }}" 
                           class="inline-flex items-center justify-center px-6 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                            <span class="text-red-500 font-bold text-lg mr-2">Я</span>
                            Яндекс
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <x-footer/>

    <script>
        function validateForm() {
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const passwordConfirmInput = document.getElementById('password_confirmation');
    
            // Валидация имени
            if (nameInput.value.length > 50) {
                alert('Имя не должно превышать 50 символов.');
                return false;
            }
    
            // Валидация email
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(emailInput.value)) {
                alert('Пожалуйста, введите корректный email (максимум 100 символов).');
                return false;
            }
    
            // Валидация пароля
            if (passwordInput.value.length < 8) {
                alert('Пароль должен содержать не менее 8 символов.');
                return false;
            }
            
            if (passwordInput.value.length > 255) {
                alert('Пароль не должен превышать 255 символов.');
                return false;
            }
    
            // Проверка сложности пароля
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/;
            if (!passwordRegex.test(passwordInput.value)) {
                alert('Пароль должен содержать хотя бы одну заглавную букву, одну строчную букву и одну цифру.');
                return false;
            }
    
            // Подтверждение пароля
            if (passwordInput.value !== passwordConfirmInput.value) {
                alert('Пароли не совпадают.');
                return false;
            }
    
            return true;
        }
    
        document.getElementById('name').addEventListener('input', function() {
            // Ограничиваем ввод только буквами, пробелами и дефисами
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s\-]/g, '');
    
            // Ограничиваем длину до 50 символов
            if (this.value.length > 50) {
                this.value = this.value.slice(0, 50);
            }
    
            // Делаем первый символ заглавным
            if (this.value.length > 0) {
                this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
            }
        });
    
        document.getElementById('email').addEventListener('input', function() {
            // Ограничиваем ввод только символами, соответствующими формату email
            this.value = this.value.replace(/[^a-zA-Z0-9._@-]/g, '');
            
            // Ограничиваем длину до 100 символов
            if (this.value.length > 100) {
                this.value = this.value.slice(0, 100);
            }
        });
    
        document.getElementById('password').addEventListener('input', function() {
            // Ограничиваем длину до 255 символов
            if (this.value.length > 255) {
                this.value = this.value.slice(0, 255);
            }
        });
    
        document.getElementById('password_confirmation').addEventListener('input', function() {
            // Ограничиваем длину до 255 символов
            if (this.value.length > 255) {
                this.value = this.value.slice(0, 255);
            }
        });
    </script>
</body>
</html>