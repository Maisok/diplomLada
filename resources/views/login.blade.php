<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Вход</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        .forgot-password {
            display: block;
            text-align: right;
            margin-top: -15px;
            margin-bottom: 20px;
            font-size: 0.875rem;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[400px] flex items-center justify-center">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Фон входа" class="absolute top-0 left-0 w-full h-full object-cover">
        <div class="relative z-20 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-6">Добро пожаловать</h1>
            <a href="#login" class="btn-primary text-white px-8 py-3 rounded-full font-medium inline-block">
                Войти в личный кабинет
            </a>
        </div>
    </section>

    <!-- Login Form -->
    <section id="login" class="py-16">
        <div class="max-w-md mx-auto px-4">
            <div class="form-container bg-white rounded-xl p-8 md:p-12">
                <h2 class="text-2xl font-bold mb-8 text-center">Вход в систему</h2>
                
                <form action="{{ route('login') }}" method="POST" class="space-y-6" onsubmit="return validateForm()">
                    @csrf
                    
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
                        <input type="password" id="password" name="password" placeholder="Ваш пароль" 
                               class="form-input w-full px-0 py-2 focus:outline-none">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full text-white py-3 rounded-full font-medium mt-6">
                        Войти
                    </button>

                    <div class="text-right mt-2">
                        <a href="{{ route('password.request') }}" class="text-sm text-[#8b5f4d] hover:underline">Забыли пароль?</a>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{route('register')}}" class="text-sm text-[#8b5f4d] hover:underline">Нет аккаунта? Зарегистрироваться</a>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{route('staff.login')}}" class="text-sm text-[#8b5f4d] hover:underline">Сотрудник?</a>
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
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
    
            // Валидация email
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(emailInput.value)) {
                alert('Пожалуйста, введите корректный email (максимум 100 символов).');
                return false;
            }
    
            // Проверка длины пароля
            if (passwordInput.value.length > 255) {
                alert('Пароль не должен превышать 255 символов.');
                return false;
            }
    
            return true;
        }
    
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
    </script>
</body>
</html>