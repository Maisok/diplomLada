<!DOCTYPE html>
<html>
<head>
    <title>Проверка сертификата</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #faf9f7;
            color: #333;
            text-align: center;
            padding: 50px 20px;
        }
        .verification-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .status-valid {
            color: #28a745;
            font-size: 24px;
            font-weight: bold;
        }
        .status-invalid {
            color: #dc3545;
            font-size: 24px;
            font-weight: bold;
        }
        .certificate-info {
            margin: 30px 0;
            text-align: left;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            width: 150px;
            color: #8b5f4d;
            font-weight: 500;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #8b5f4d;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h1>Проверка сертификата</h1>
        <p>Номер сертификата: <strong>{{ $certificate->code }}</strong></p>
        
        @if($isValid)
            <div class="status-valid">✓ Сертификат действителен</div>
        @else
            <div class="status-invalid">
                @if($certificate->is_used)
                    ✗ Сертификат уже использован
                @else
                    ✗ Сертификат недействителен (просрочен)
                @endif
            </div>
        @endif
        
        <div class="certificate-info">
            <div class="info-row">
                <div class="info-label">Номинал:</div>
                <div>{{ number_format($certificate->amount, 0, ',', ' ') }} ₽</div>
            </div>
            <div class="info-row">
                <div class="info-label">Действителен до:</div>
                <div>{{ $certificate->expires_at }}</div>
            </div>
            @if($certificate->recipient_name)
            <div class="info-row">
                <div class="info-label">Для:</div>
                <div>{{ $certificate->recipient_name }}</div>
            </div>
            @endif
        </div>
        
        <a href="{{ url('/') }}" class="back-link">Вернуться на главную</a>
    </div>
</body>
</html>