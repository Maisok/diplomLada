<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Административная панель</title>
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
        .admin-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-admin {
            background-color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-admin:hover {
            background-color: #6d4a3a;
        }
        .btn-secondary {
            background-color: #f5f0ed;
            color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #e8e0dc;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Административная панель" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Административная панель</h1>
            <p class="text-white text-lg max-w-2xl">Управление всеми аспектами работы салона красоты</p>
        </div>
    </section>

    <!-- Admin Dashboard Section -->
    <section class="py-12 px-4 max-w-6xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold mb-2">Добро пожаловать, администратор!</h2>
                <p class="text-gray-600">Выберите раздел для управления</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Services Card -->
                <a href="{{ route('admin.services.index') }}" class="admin-card bg-white rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#f5f0ed] p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8b5f4d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold">Управление услугами</h3>
                        </div>
                        <p class="text-gray-600">Добавление, редактирование и удаление услуг салона</p>
                    </div>
                </a>
                
                <!-- Staff Card -->
                <a href="{{ route('admin.staff.index') }}" class="admin-card bg-white rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#f5f0ed] p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8b5f4d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold">Управление сотрудниками</h3>
                        </div>
                        <p class="text-gray-600">Редактирование информации о сотрудниках салона</p>
                    </div>
                </a>
                
                <!-- Branches Card -->
                <a href="{{ route('admin.branches.index') }}" class="admin-card bg-white rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#f5f0ed] p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8b5f4d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold">Филиалы</h3>
                        </div>
                        <p class="text-gray-600">Управление филиалами и их расписанием</p>
                    </div>
                </a>
                
                <!-- Appointments Export Card -->
                <a href="{{ route('export.appointments') }}" class="admin-card bg-white rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#f5f0ed] p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8b5f4d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold">Экспорт записей</h3>
                        </div>
                        <p class="text-gray-600">Скачать полный список всех записей</p>
                    </div>
                </a>
                
                <!-- New Appointments Export Card -->
                <a href="{{ route('export.new-appointments') }}" class="admin-card bg-white rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#f5f0ed] p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8b5f4d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold">Экспорт новых записей</h3>
                        </div>
                        <p class="text-gray-600">Скачать список новых записей за последние 7 дней</p>
                    </div>
                </a>

                <!-- Appointments Management Card -->
                <a href="{{ route('admin.appointments.index') }}" class="admin-card bg-white rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#f5f0ed] p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8b5f4d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold">Управление записями</h3>
                        </div>
                        <p class="text-gray-600">Просмотр и изменение статусов записей</p>
                    </div>
                </a>
                
                <!-- Gift Certificates Card -->
                <a href="{{ route('admin.certificates.index') }}" class="admin-card bg-white rounded-lg overflow-hidden border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-[#f5f0ed] p-3 rounded-full mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#8b5f4d]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2m8 0h4a2 2 0 012 2v2m0 0a2 2 0 01-2 2h-4m-8 0H6a2 2 0 01-2-2v-2m0 0a2 2 0 012-2h4" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold">Подарочные сертификаты</h3>
                        </div>
                        <p class="text-gray-600">Просмотр и управление подарочными сертификатами</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <x-footer/>
</body>
</html>