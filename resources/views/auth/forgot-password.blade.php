<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Восстановление пароля</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
              body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background-color: #faf9f7;
        }
        .verification-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            color: #8b5f4d;
            margin-bottom: 20px;
        }
        .verification-message {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            line-height: 1.6;
        }
        .btn-primary {
            background-color: #8b5f4d;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 30px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
            text-decoration: none;
        }
        .btn-primary:hover {
            background-color: #6d4a3a;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .resend-link {
            color: #8b5f4d;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: color 0.3s;
        }
        .resend-link:hover {
            color: #6d4a3a;
            text-decoration: underline;
        }и стили ... */
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <section class="relative h-[300px] md:h-[400px] flex items-center justify-center">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Фон восстановления пароля" class="absolute top-0 left-0 w-full h-full object-cover">
        <div class="relative z-20 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Восстановление пароля</h1>
        </div>
    </section>

    <section class="py-16 px-4">
        <div class="max-w-md mx-auto">
            <div class="form-container bg-white p-8 md:p-12">
                <h2 class="text-2xl font-bold mb-8 text-center">Сбросить пароль</h2>
                
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium mb-2" for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Ваш email" 
                               class="form-input w-full px-0 py-2 focus:outline-none">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="btn-primary w-full text-white py-3 rounded-full font-medium">
                        Отправить ссылку для сброса
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-sm text-[#8b5f4d] hover:underline">Вернуться к входу</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <x-footer/>
</body>
</html>