<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'Playfair Display', 'DejaVu Sans', serif;
            margin: 0;
            padding: 20px;
            background-color: white;
            color: #333;
        }
        .certificate {
            width: 100%;
            min-height: 95vh;
            margin: 0 auto;
            padding: 30px;
            box-sizing: border-box;
            border: 1px solid #e0e0e0;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #8b5f4d;
        }
        .title {
            color: #8b5f4d;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 14px;
            color: #8b5f4d;
            letter-spacing: 1px;
        }
        .content {
            margin: 20px auto;
            max-width: 500px;
            line-height: 1.6;
        }
        .certificate-info {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            width: 120px;
            color: #8b5f4d;
            font-weight: 500;
        }
        .info-value {
            flex: 1;
        }
        .message-box {
            padding: 10px;
            margin: 15px 0;
            font-style: italic;
            background-color: #f9f9f9;
        }
        .verification-section {
            text-align: center;
            margin: 20px 0;
            padding-top: 15px;
            border-top: 1px dashed #8b5f4d;
        }
        .verification-code {
            font-family: monospace;
            background-color: #f5f5f5;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .footer {
            margin-top: 20px;
            font-size: 11px;
            text-align: center;
            color: #999;
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <div class="header">
            <div class="title">ПОДАРОЧНЫЙ СЕРТИФИКАТ</div>
            <div class="subtitle">Салон красоты BB</div>
        </div>
        
        <div class="content">
            <div class="certificate-info">
                <div class="info-row">
                    <div class="info-label">Номер:</div>
                    <div class="info-value"><strong>{{ $certificate->code }}</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Номинал:</div>
                    <div class="info-value"><strong>{{ number_format($certificate->amount, 0, ',', ' ') }} ₽</strong></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Действителен до:</div>
                    <div class="info-value">{{ $certificate->expires_at }}</div>
                </div>
                
                @if($certificate->recipient_name)
                <div class="info-row">
                    <div class="info-label">Получатель:</div>
                    <div class="info-value">{{ $certificate->recipient_name }}</div>
                </div>
                @endif
            </div>
            
            @if($certificate->message)
            <div class="message-box">
                {{ $certificate->message }}
            </div>
            @endif
            
            <div class="verification-section">
                <p>Для проверки подлинности используйте код:</p>
                <div class="verification-code">{{ $certificate->code }}</div>
                <p style="margin-top: 10px; font-size: 12px;">
                    или перейдите по ссылке:<br>
                    {{ route('gift-certificates.verify', $certificate->code) }}
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>Сертификат выдан {{ $certificate->created_at }} | Салон красоты BB | bb-beauty.ru</p>
        </div>
    </div>
</body>
</html>