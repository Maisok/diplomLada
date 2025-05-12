<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Beauty Bar</title>
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
            background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        .service-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #6d4a3a;
        }
        .form-input {
            border-bottom: 1px solid #ddd;
            background: transparent;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-bottom-color: #8b5f4d;
        }
        .price-tag {
            background-color: #8b5f4d;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.9rem;
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
    </style>
</head>
<body class="antialiased">

    <x-header/>
    
    <div class="">
        <!-- Hero Section -->
        <section class="relative h-screen max-h-[600px] overflow-hidden">
            <div class="absolute inset-0 hero-overlay z-10"></div>
            <img src="{{asset('img/main.png')}}" alt="Салон красоты BB" class="w-full h-full object-cover">
            <div class="absolute bottom-0 left-0 z-20 p-8 md:p-12 text-white max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-bold leading-tight mb-4">Совершенный подход к вашей красоте</h1>
                <p class="text-lg md:text-xl opacity-90 mb-6">Профессиональный уход и индивидуальный подход для каждого клиента</p>
                <a href="{{route('services')}}" class="btn-primary text-white px-8 py-3 rounded-full font-medium inline-block">Записаться онлайн</a>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-16 px-4 max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Наши услуги</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Мы предлагаем комплексный уход с использованием профессиональной косметики и современных методик</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($randomServices as $service)
                <div class="service-card bg-white rounded-lg overflow-hidden">
                    <div class="h-64 overflow-hidden relative">
                        @if($service->image)
                            <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        <div class="price-tag">
                            {{ number_format($service->price, 0, ',', ' ') }} ₽
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                        <a href="{{route('services')}}" class="text-sm font-medium text-[#8b5f4d] hover:underline">Подробнее →</a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
    
    <x-footer/>

    <script>
        function formatPhoneNumber(input) {
            let phoneNumber = input.value.replace(/\D/g, '');
            
            if (phoneNumber.length > 1) {
                phoneNumber = phoneNumber.substring(0, 1) + ' ' + phoneNumber.substring(1);
            }
            if (phoneNumber.length > 5) {
                phoneNumber = phoneNumber.substring(0, 5) + ' ' + phoneNumber.substring(5);
            }
            if (phoneNumber.length > 9) {
                phoneNumber = phoneNumber.substring(0, 9) + ' ' + phoneNumber.substring(9);
            }
            if (phoneNumber.length > 12) {
                phoneNumber = phoneNumber.substring(0, 12) + ' ' + phoneNumber.substring(12);
            }
            
            input.value = phoneNumber.substring(0, 16);
        }

        function validateForm() {
            const phoneInput = document.getElementById('phone');
            const phoneNumber = phoneInput.value.replace(/\D/g, '');

            if (phoneNumber.length < 11) {
                alert('Пожалуйста, введите корректный номер телефона (11 цифр)');
                return false;
            }

            return true;
        }

        document.getElementById('phone').addEventListener('input', function() {
            formatPhoneNumber(this);
        });

        document.getElementById('name').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
            
            if (this.value.length > 0) {
                this.value = this.value.charAt(0).toUpperCase() + this.value.slice(1);
            }
        });
    </script>
</body>
</html>