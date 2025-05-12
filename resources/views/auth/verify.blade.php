<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение Email | BB</title>
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
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h1>Подтвердите ваш Email</h1>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-primary">Отправить письмо повторно</button>
        </form>
        
        <a href="{{ route('welcome') }}" 
           class="resend-link">
            На главную
        </a>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</body>
</html>