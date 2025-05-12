<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Услуги</title>
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
            background: linear-gradient(90deg, rgba(139,95,77,0.7) 0%, rgba(139,95,77,0.3) 100%);
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
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #8b5f4d;
            box-shadow: 0 0 0 2px rgba(139, 95, 77, 0.2);
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/7.png')}}" alt="Наши услуги" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex items-center justify-center text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white">Наши услуги</h1>
        </div>
    </section>

    <!-- Search Section -->
    <section class="py-8 bg-[#f5f0ed]">
        <div class="max-w-4xl mx-auto px-4">
            <form action="{{ route('services') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Поиск по названию услуги" 
                    class="form-input flex-grow px-4 py-3 rounded-full focus:outline-none"
                    value="{{ request('search') }}"
                >
                <button type="submit" class="btn-primary text-white px-6 py-3 rounded-full font-medium">
                    Найти
                </button>
            </form>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
            <a href="{{ route('services.show', $service) }}" class="service-card bg-white rounded-lg overflow-hidden relative">
                @if(auth()->check() && $service->hasUnreadQuestions(auth()->id()))
                <span class="absolute top-2 right-2 block h-3 w-3 rounded-full bg-red-500"></span>
                @endif
                <div class="h-64 overflow-hidden">
                    <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-full h-full object-cover transition duration-500 hover:scale-105">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $service->name }}</h3>
                    <p class="text-[#8b5f4d] font-medium">{{ number_format($service->price, 0, ',', ' ') }} ₽</p>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    <x-footer/>
</body>
</html>