<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение изменения email | BB</title>
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
        .email-change-info {
            background-color: #f8f5f2;
            border-left: 4px solid #8b5f4d;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .email-address {
            font-weight: 600;
            color: #8b5f4d;
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
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #6d4a3a;
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
            text-decoration: none;
            border-bottom: 1px dashed #8b5f4d;
        }
        .divider {
            height: 1px;
            background-color: #eee;
            margin: 25px 0;
        }
        .warning-note {
            font-size: 13px;
            color: #666;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        
        <div class="content">
            <h1>Подтвердите изменение email</h1>
            
            <div class="email-change-info">
                <p>Вы запросили изменение email адреса:</p>
                <p>
                 
                    <span class="email-address">{{ $user->new_email }}</span>
                </p>
            </div>
            
            <p>Для завершения процесса изменения, пожалуйста, подтвердите новый email адрес:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" class="button">Подтвердить изменение</a>
            </div>
            
            <div class="divider"></div>
            
            <p>Если кнопка выше не работает, скопируйте и вставьте следующую ссылку в адресную строку браузера:</p>
            
            <a href="{{ $verificationUrl }}" class="link">{{ $verificationUrl }}</a>
            
            <p class="warning-note">
                Если вы не запрашивали это изменение, пожалуйста, проигнорируйте это письмо.
            </p>
        </div>
        
        <div class="footer">
            © {{ date('Y') }} BB. Все права защищены.
        </div>
    </div>
</body>
</html>