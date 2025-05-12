<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Панель персонала</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background-color: #faf9f7;
        }
        h1, h2, h3, h4 {
            font-family: 'Playfair Display', serif;
        }
        .sidebar {
            transition: all 0.3s ease;
        }
        .nav-item {
            transition: all 0.2s ease;
        }
        .nav-item:hover {
            background-color: rgba(139, 95, 77, 0.1);
        }
        .nav-item.active {
            background-color: rgba(139, 95, 77, 0.2);
            border-left: 3px solid #8b5f4d;
        }
        .card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #6d4a3a;
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="antialiased">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-white shadow-md fixed h-full">
            <div class="p-6 flex items-center justify-center border-b border-gray-100">
                <img src="{{ asset('img/logo.png') }}" alt="BB Logo" class="h-10">
                <span class="ml-3 text-xl font-bold">Beauty Bar</span>
            </div>
            
            <!-- User Profile -->
            <div class="p-6 flex items-center border-b border-gray-100">
                @if(auth('staff')->user()->image)
                    <img src="{{ asset(auth('staff')->user()->image) }}" alt="Profile" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                @endif
                <div class="ml-4">
                    <h4 class="font-semibold">{{ auth('staff')->user()->first_name }} {{ auth('staff')->user()->last_name }}</h4>
                    <p class="text-sm text-gray-600">{{ auth('staff')->user()->position }}</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4">
                <ul>
                    <li>
                        <a href="{{ route('staff.dashboard') }}" class="nav-item flex items-center px-4 py-3 rounded-lg text-gray-700 active" >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span class="ml-3">Главная</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.appointments.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="ml-3">Записи</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('staff.services.index') }}" class="nav-item flex items-center px-4 py-3 rounded-lg text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="ml-3">Услуги</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <!-- Logout -->
            <div class="absolute bottom-0 w-full p-4">
                <form action="{{ route('staff.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="ml-3">Выйти</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Header -->
            <header class="bg-white shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Панель управления</h2>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-600 mr-4">{{ now()->format('d.m.Y') }}</span>
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <main class="p-6" id="dynamic-content">
                <!-- Welcome Card -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
                    <div class="p-6 bg-gradient-to-r from-amber-50 to-amber-100">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div>
                                <h3 class="text-xl font-bold mb-2">Добро пожаловать, {{ auth('staff')->user()->first_name }}!</h3>
                                <p class="text-gray-700">
                                    @if($todayAppointmentsCount > 0)
                                        Сегодня у вас запланировано {{ $todayAppointmentsCount }} {{ trans_choice('запись|записи|записей', $todayAppointmentsCount) }}.
                                        @if($lastAppointmentToday)
                                            Последняя запись в {{ $lastAppointmentToday->start_time->format('H:i') }}.
                                        @endif
                                    @else
                                        Сегодня у вас нет запланированных записей.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="card bg-white rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-gray-500">Сегодня</h4>
                                <p class="text-2xl font-bold">{{ $todayAppointmentsCount }} {{ trans_choice('запись|записи|записей', $todayAppointmentsCount) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-white rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-gray-500">За месяц</h4>
                                <p class="text-2xl font-bold">{{ $monthAppointmentsCount }} {{ trans_choice('запись|записи|записей', $monthAppointmentsCount) }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card bg-white rounded-lg p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-gray-500">Выручка</h4>
                                <p class="text-2xl font-bold">{{ number_format($monthRevenue, 0, ',', ' ') }} ₽</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Appointments -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold">Ближайшие записи</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($upcomingAppointments as $appointment)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h4 class="font-medium">{{ $appointment->service->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        {{ $appointment->user->name }} - {{ $appointment->start_time->format('H:i') }}
                                    </p>
                                </div>
                                <div class="ml-auto">
                                    @if($appointment->status == 'confirmed')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Подтверждена</span>
                                    @elseif($appointment->status == 'pending')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Ожидает</span>
                                    @elseif($appointment->status == 'cancelled')
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Отменена</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-4 text-center text-gray-500">
                            Нет предстоящих записей
                        </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
        // Обработка кликов по навигации
        document.querySelectorAll('.nav-item').forEach(link => {
            link.addEventListener('click', function(e) {
                // Проверяем, не является ли ссылка уже активной
                if (this.classList.contains('active')) {
                    e.preventDefault();
                    return;
                }
                
                e.preventDefault();
                
                // Удаляем активный класс у всех элементов
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Добавляем активный класс текущему элементу
                this.classList.add('active');
                
                // Показываем индикатор загрузки
                showLoadingIndicator();
                
                // Загружаем контент через AJAX
                loadContent(this.href);
            });
        });
            
            // Функция для показа индикатора загрузки
            function showLoadingIndicator() {
                document.getElementById('dynamic-content').innerHTML = `
                    <div class="flex justify-center items-center h-64">
                        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-amber-500"></div>
                    </div>
                `;
            }
            
            // Функция для загрузки контента
            function loadContent(url) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    if (data.content) {
                        document.getElementById('dynamic-content').innerHTML = data.content;
                        history.pushState(null, null, url);
                        updatePageTitle(url);
                    } else {
                        window.location.href = url;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorIndicator(error.message || 'Произошла ошибка при загрузке страницы');
                });
            }

        function showErrorIndicator(message) {
            document.getElementById('dynamic-content').innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">
                                ${message}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
            
            // Функция для показа ошибки
            function showErrorIndicator() {
                document.getElementById('dynamic-content').innerHTML = `
                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Произошла ошибка при загрузке страницы. Пожалуйста, попробуйте еще раз.
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Функция для обновления заголовка страницы
            function updatePageTitle(url) {
                const routeMap = {
                    'staffauth/dashboard': 'Главная',
                    'staffauth/appointments': 'Записи',
                    'staffauth/clients': 'Клиенты',
                    'staffauth/services': 'Услуги'
                };
                
                for (const [route, title] of Object.entries(routeMap)) {
                    if (url.includes(route)) {
                        document.title = `BB | ${title}`;
                        document.querySelector('header h2').textContent = title;
                        break;
                    }
                }
            }
            
            // Обработка кнопки "назад" в браузере
            window.addEventListener('popstate', function() {
                showLoadingIndicator();
                loadContent(window.location.href);
            });
            
            // Инициализация активного элемента при загрузке страницы
            function initActiveNavItem() {
                const currentPath = window.location.pathname;
                document.querySelectorAll('.nav-item').forEach(item => {
                    const itemPath = new URL(item.href).pathname;
                    if (itemPath === currentPath) {
                        item.classList.add('active');
                    }
                });
            }
            
            initActiveNavItem();
        });
    </script>

<script>
    function updateStatus(selectElement) {
        const appointmentId = selectElement.dataset.appointmentId;
        const newStatus = selectElement.value;
        
        // Показываем индикатор загрузки
        selectElement.disabled = true;
        selectElement.classList.add('opacity-50');
        
        fetch(`/staffauth/appointments/${appointmentId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка при обновлении статуса');
            }
            return response.json();
        })
        .then(data => {
            // Успешное обновление
            const badgeColors = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'active': 'bg-green-100 text-green-800',
                'completed': 'bg-blue-100 text-blue-800',
                'cancelled': 'bg-red-100 text-red-800'
            };
            
            const statusTexts = {
                'pending': 'Ожидает',
                'active': 'Подтверждена',
                'completed': 'Выполнена',
                'cancelled': 'Отменена'
            };
            
            // Можно добавить визуальную обратную связь
            const row = document.getElementById(`appointment-${appointmentId}`);
            row.classList.add('bg-green-50');
            setTimeout(() => row.classList.remove('bg-green-50'), 1000);
        })
        .catch(error => {
            console.error('Error:', error);
            // Восстанавливаем предыдущее значение
            selectElement.value = selectElement.dataset.originalValue;
            alert('Произошла ошибка при обновлении статуса');
        })
        .finally(() => {
            selectElement.disabled = false;
            selectElement.classList.remove('opacity-50');
        });
    }
    
    // Сохраняем оригинальные значения при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.status-select').forEach(select => {
            select.dataset.originalValue = select.value;
        });
    });
    </script>

    
<script>
    function updateStatus(selectElement) {
        const appointmentId = selectElement.dataset.appointmentId;
        const newStatus = selectElement.value;
        const container = document.getElementById(`status-container-${appointmentId}`);
        
        // Проверяем допустимые переходы
        if (!['completed', 'cancelled'].includes(newStatus)) {
            return;
        }
        
        // Сразу заменяем селект на текстовый статус с анимацией загрузки
        let statusHtml;
        if (newStatus === 'completed') {
            statusHtml = `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 animate-pulse">Выполнена</span>`;
        } else {
            statusHtml = `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 animate-pulse">Отменена</span>`;
        }
        container.innerHTML = statusHtml;
        
        // Отправляем запрос на сервер
        fetch(`/staffauth/appointments/${appointmentId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                status: newStatus
            })
        })
        .then(response => {
            if (!response.ok) throw new Error('Ошибка при обновлении статуса');
            return response.json();
        })
        .then(data => {
            // Убираем анимацию после успешного обновления
            if (newStatus === 'completed') {
                container.innerHTML = `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Выполнена</span>`;
            } else {
                container.innerHTML = `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Отменена</span>`;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // В случае ошибки возвращаем селект
            container.innerHTML = `
                <select class="status-select appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                        data-appointment-id="${appointmentId}"
                        onchange="updateStatus(this)">
                    <option value="active" selected>Подтверждена</option>
                    <option value="completed">Выполнена</option>
                    <option value="cancelled">Отменена</option>
                </select>`;
            alert('Произошла ошибка при обновлении статуса');
        });
    }
    </script>
</body>
</html>