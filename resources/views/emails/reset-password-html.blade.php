<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля | BB</title>
    <style>
        /* Основные стили */
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            background-color: #faf9f7;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #8b5f4d;
            padding: 30px 20px;
            text-align: center;
        }
        .logo {
            height: 50px;
        }
        .content {
            padding: 30px;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            color: #8b5f4d;
            margin-top: 0;
            font-size: 24px;
        }
        .button {
            display: inline-block;
            background-color: #8b5f4d;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 30px;
            margin: 20px 0;
            font-weight: 500;
            text-align: center;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .link {
            word-break: break-all;
            color: #8b5f4d;
            font-size: 14px;
            margin: 15px 0;
            display: inline-block;
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        
        <!-- Основное содержимое -->
        <div class="content">
            <h1>Восстановление пароля</h1>
            
            <p>Вы получили это письмо, потому что был запрошен сброс пароля для вашей учетной записи.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $url }}" class="button">Сбросить пароль</a>
            </div>
            
            <div class="divider"></div>
            
            <p>Если кнопка выше не работает, скопируйте и вставьте следующую ссылку в адресную строку браузера:</p>
            
            <a href="{{ $url }}" class="link">{{ $url }}</a>
            
            <p>Ссылка действительна в течение 60 минут. Если вы не запрашивали сброс пароля, проигнорируйте это письмо.</p>
            
            <p>С уважением,<br>Команда BB</p>
        </div>
        
        <!-- Подвал письма -->
        <div class="footer">
            © {{ date('Y') }} BB. Все права защищены.<br>
            <small>Если вы не запрашивали сброс пароля, пожалуйста, проигнорируйте это письмо.</small>
        </div>
    </div>
</body>
</html>