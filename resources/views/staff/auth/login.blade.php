<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Вход для персонала</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        .form-input {
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #8b5f4d;
            box-shadow: 0 0 0 2px rgba(139, 95, 77, 0.2);
        }
        .btn-primary {
            background-color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #6d4a3a;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="antialiased bg-gray-100">
    <x-header/>
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Вход для персонала" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex items-center justify-center text-center px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-white">Вход для персонала</h1>
        </div>
    </section>

    <!-- Login Form -->
    <main class="py-12 px-4 max-w-md mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold">Добро пожаловать</h2>
                <p class="text-gray-600 mt-2">Введите ваши учетные данные для входа</p>
            </div>

            <form method="POST" action="{{ route('staff.login.post') }}">
                @csrf
                <div class="space-y-6">
                    @if($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700">
                                        {{ $errors->first() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 mb-1">Логин (5 цифр)</label>
                        <input type="text" id="login" name="login" class="form-input w-full px-4 py-3 rounded-lg" 
                               required pattern="\d{5}" title="Логин должен состоять из 5 цифр" maxlength="5"
                               value="{{ old('login') }}">
                    </div>

                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Пароль</label>
                        <input type="password" id="password" name="password" class="form-input w-full px-4 py-3 rounded-lg" required>
                        <span class="password-toggle" onclick="togglePassword('password')">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-brown-600 focus:ring-brown-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Запомнить меня</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn-primary w-full py-3 px-4 rounded-lg text-white font-medium">
                            Войти
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }

        // Format login input (only digits)
        document.getElementById('login').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 5) {
                this.value = this.value.slice(0, 5);
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const login = document.getElementById('login').value;
            
            if (!/^\d{5}$/.test(login)) {
                e.preventDefault();
                alert('Логин должен состоять из 5 цифр.');
                return;
            }
        });
    </script>
</body>
</html>