<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | {{ $service->name }}</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://api-maps.yandex.ru/2.1/?apikey=fa8f0187-f7ba-4f94-aa91-c6a61473cec3&lang=ru_RU" type="text/javascript"></script>
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
        #map {
            width: 100%;
            height: 300px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/7.png')}}" alt="{{ $service->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex items-center justify-center text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $service->name }}</h1>
        </div>
    </section>

    <!-- Success Message -->
    @if(session('success'))
    <div class="fixed top-4 right-4 z-50">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{!! nl2br(e(session('success'))) !!}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none'">
                    <title>Close</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    </div>
    @endif

    <!-- Service Details Section -->
    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 rounded-md">
            <ul class="text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
                    <!-- Service Image -->
            <div class="service-card bg-white rounded-lg overflow-hidden">
                <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-full h-96 object-cover">
                <!-- Яндекс Карта -->
                <div id="map"></div>
            </div>

            <!-- Service Info -->
            <div class="bg-white rounded-lg p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold mb-2">{{ $service->name }}</h2>
                    <p class="text-[#8b5f4d] text-xl font-medium">{{ number_format($service->price, 0, ',', ' ') }} ₽</p>
                </div>

                <!-- Booking Form -->
                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-4">Записаться на услугу</h3>
                    <form action="{{ route('services.store-appointment', $service) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <!-- Выбор филиала -->
                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 mb-1">Филиал</label>
                            <select 
                                name="branch_id" 
                                id="branch_id" 
                                required
                                class="form-input w-full px-4 py-3 rounded-md"
                                onchange="loadStaff(this.value); updateMap(this.options[this.selectedIndex].getAttribute('data-address'), this.options[this.selectedIndex].getAttribute('data-coords'));"
                            >
                                <option value="">Выберите филиал</option>
                                @foreach($branches as $branch)
                                    <option 
                                        value="{{ $branch->id }}" 
                                        data-address="{{ $branch->address }}"
                                        data-coords="{{ $branch->latitude }},{{ $branch->longitude }}"
                                    >
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Выбор специалиста -->
                        <div>
                            <label for="staff_id" class="block text-sm font-medium text-gray-700 mb-1">Специалист</label>
                            <select 
                                name="staff_id" 
                                id="staff_id" 
                                required 
                                disabled
                                class="form-input w-full px-4 py-3 rounded-md"
                                onchange="updateTimeSlots()"
                            >
                                <option value="">Сначала выберите филиал</option>
                            </select>
                        </div>
                        
                        <!-- Выбор даты -->
                        <div>
                            <label for="appointmentDate" class="block text-sm font-medium text-gray-700 mb-1">Дата записи</label>
                            <input 
                                type="date" 
                                id="appointmentDate" 
                                name="date" 
                                class="form-input w-full px-4 py-3 rounded-md" 
                                required
                                min="{{ date('Y-m-d') }}"
                                max="{{ date('Y-m-d', strtotime('+1 month')) }}"
                                onchange="updateTimeSlots()"
                            >
                        </div>
                        
                        <!-- Выбор времени -->
                        <div>
                            <label for="appointmentTime" class="block text-sm font-medium text-gray-700 mb-1">Время записи</label>
                            <select 
                                name="time" 
                                id="appointmentTime" 
                                required 
                                disabled
                                class="form-input w-full px-4 py-3 rounded-md"
                            >
                                <option value="">Сначала выберите филиал, специалиста и дату</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn-primary w-full text-white py-3 rounded-md font-medium mt-6">
                            Записаться
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="bg-white rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Вопросы и ответы</h2>
            
            <!-- Форма для нового вопроса -->
            @auth
            <div class="mb-8">
                <form action="{{ route('services.questions.store', $service) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="question" class="block text-sm font-medium text-gray-700 mb-1">Задать вопрос</label>
                        <textarea name="question" id="question" rows="3" class="form-input w-full px-4 py-3 rounded-md" required></textarea>
                    </div>
                    <button type="submit" class="btn-primary text-white px-6 py-2 rounded-md font-medium">
                        Отправить вопрос
                    </button>
                </form>
            </div>
            @else
            <div class="mb-6 p-4 bg-gray-50 rounded-md">
                <p class="text-gray-600">Чтобы задать вопрос, пожалуйста <a href="{{ route('login') }}" class="text-[#8b5f4d] font-medium">войдите</a> или <a href="{{ route('register') }}" class="text-[#8b5f4d] font-medium">зарегистрируйтесь</a>.</p>
            </div>
            @endauth
            
            <!-- Список вопросов -->
            <div class="space-y-8">
                @foreach($service->questions->sortByDesc('created_at') as $question)
                <div class="border-b border-gray-200 pb-6 last:border-0">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <h3 class="font-semibold">{{ $question->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $question->created_at->format('d.m.Y H:i') }}</p>
                        </div>
                        @if(auth()->id() === $question->user_id)
                        <div class="flex space-x-2">
                            @if(!$question->is_closed && $question->answers->isEmpty())
                            <form action="{{ route('services.questions.destroy', ['service' => $service, 'question' => $question]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Удалить вопрос</button>
                            </form>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('edit-question-{{ $question->id }}').classList.toggle('hidden');" class="text-[#8b5f4d] hover:text-[#6d4a3a] text-sm">Редактировать</a>
                            @endif
                            @if(!$question->is_closed && $question->answers->isNotEmpty())
                            <form action="{{ route('services.questions.close', $question) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[#8b5f4d] hover:text-[#6d4a3a] text-sm">Закрыть обсуждение</button>
                            </form>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <!-- Форма редактирования вопроса (скрыта по умолчанию) -->
                    <form id="edit-question-{{ $question->id }}" action="{{ route('services.questions.update', $question) }}" method="POST" class="hidden mb-4">
                        @csrf
                        @method('PUT')
                        <textarea name="question" rows="3" class="form-input w-full px-4 py-3 rounded-md mb-2">{{ $question->question }}</textarea>
                        <div class="flex space-x-2">
                            <button type="submit" class="btn-primary text-white px-4 py-1 rounded-md text-sm">Сохранить</button>
                            <button type="button" onclick="document.getElementById('edit-question-{{ $question->id }}').classList.add('hidden')" class="bg-gray-200 text-gray-700 px-4 py-1 rounded-md text-sm">Отмена</button>
                        </div>
                    </form>
                    
                    <!-- Текст вопроса -->
                    <p class="mb-4">{{ $question->question }}</p>
                    
                    <!-- Ответы -->
                    <div class="ml-8 pl-4 border-l-2 border-gray-200 space-y-4">
                        @foreach($question->answers as $answer)
                        <div class="pt-4">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-semibold">{{ $answer->user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $answer->created_at->format('d.m.Y H:i') }}</p>
                                </div>
                                @if(auth()->id() === $answer->user_id)
                                <div class="flex space-x-2">
                                    <form action="{{ route('services.answers.destroy', $answer) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Удалить</button>
                                    </form>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('edit-answer-{{ $answer->id }}').classList.toggle('hidden');" class="text-[#8b5f4d] hover:text-[#6d4a3a] text-sm">Редактировать</a>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Форма редактирования ответа (скрыта по умолчанию) -->
                            <form id="edit-answer-{{ $answer->id }}" action="{{ route('services.answers.update', $answer) }}" method="POST" class="hidden mb-4">
                                @csrf
                                @method('PUT')
                                <textarea name="answer" rows="2" class="form-input w-full px-4 py-3 rounded-md mb-2">{{ $answer->answer }}</textarea>
                                <div class="flex space-x-2">
                                    <button type="submit" class="btn-primary text-white px-4 py-1 rounded-md text-sm">Сохранить</button>
                                    <button type="button" onclick="document.getElementById('edit-answer-{{ $answer->id }}').classList.add('hidden')" class="bg-gray-200 text-gray-700 px-4 py-1 rounded-md text-sm">Отмена</button>
                                </div>
                            </form>
                            
                            <!-- Текст ответа -->
                            <p>{{ $answer->answer }}</p>
                        </div>
                        @endforeach
                        
                        <!-- Форма для нового ответа -->
                        @auth
                        @if(!$question->is_closed)
                        <div class="pt-4">
                            <form action="{{ route('services.answers.store', $question) }}" method="POST">
                                @csrf
                                <div class="mb-2">
                                    <textarea name="answer" rows="2" class="form-input w-full px-4 py-3 rounded-md" placeholder="Написать ответ..." required></textarea>
                                </div>
                                <button type="submit" class="btn-primary text-white px-4 py-1 rounded-md text-sm">Ответить</button>
                            </form>
                        </div>
                        @endif
                        @endauth
                    </div>
                </div>
                @endforeach
                
                @if($service->questions->isEmpty())
                <p class="text-gray-500">Пока нет вопросов. Будьте первым, кто задаст вопрос!</p>
                @endif
            </div>
        </div>
    </section>

    <x-footer/>

    <script>
          // Инициализация карты
    let map;
    let placemark;
    
    ymaps.ready(initMap);
    
    function initMap() {
        map = new ymaps.Map("map", {
            center: [55.76, 37.64], // Москва по умолчанию
            zoom: 10
        });
        
        // Упрощаем элементы управления
        map.controls.remove('geolocationControl');
        map.controls.remove('searchControl');
        map.controls.remove('trafficControl');
        map.controls.remove('typeSelector');
        map.controls.remove('fullscreenControl');
        map.controls.remove('rulerControl');
    }
    
    // Функция для геокодирования адреса и центрирования карты
    function geocodeAndCenter(address) {
        if (!address) return;
        
        // Удаляем предыдущую метку, если есть
        if (placemark) {
            map.geoObjects.remove(placemark);
        }
        
        // Используем геокодер Яндекс.Карт
        ymaps.geocode(address, {
            results: 1
        }).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);
            
            if (firstGeoObject) {
                // Получаем координаты
                var coords = firstGeoObject.geometry.getCoordinates();
                
                // Центрируем карту
                map.setCenter(coords, 15);
                
                // Добавляем метку
                placemark = new ymaps.Placemark(coords, {
                    hintContent: address,
                    balloonContent: address
                });
                
                map.geoObjects.add(placemark);
            } else {
                console.error('Адрес не найден: ' + address);
            }
        }).catch(function (error) {
            console.error('Ошибка геокодирования: ', error);
        });
    }
    
    // Обновленная функция для обновления карты
    function updateMap(address) {
        if (!address) return;
        geocodeAndCenter(address);
    }
        
        // Загрузка специалистов для выбранного филиала
        function loadStaff(branchId) {
            const staffSelect = document.getElementById('staff_id');
            
            if (!branchId) {
                staffSelect.disabled = true;
                staffSelect.innerHTML = '<option value="">Сначала выберите филиал</option>';
                return;
            }
            
            fetch(`/services/{{ $service->id }}/staff?branch_id=${branchId}`)
                .then(response => response.json())
                .then(data => {
                    staffSelect.disabled = false;
                    staffSelect.innerHTML = '<option value="">Выберите специалиста</option>';
                    
                    data.forEach(staff => {
                        const option = document.createElement('option');
                        option.value = staff.id;
                        option.textContent = staff.first_name + ' ' + staff.last_name;
                        staffSelect.appendChild(option);
                    });
                    
                    updateTimeSlots();
                });
        }
        
// Обновление доступных временных слотов
function updateTimeSlots() {
    const staffId = document.getElementById('staff_id').value;
    const date = document.getElementById('appointmentDate').value;
    const branchId = document.getElementById('branch_id').value;
    const timeSelect = document.getElementById('appointmentTime');
    
    if (!staffId || !date || !branchId) {
        timeSelect.disabled = true;
        timeSelect.innerHTML = '<option value="">Сначала выберите филиал, специалиста и дату</option>';
        return;
    }
    
    // Показываем загрузку
    timeSelect.disabled = true;
    timeSelect.innerHTML = '<option value="">Загрузка доступного времени...</option>';
    
    fetch(`/services/{{ $service->id }}/times?staff_id=${staffId}&date=${date}&branch_id=${branchId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Ошибка при получении доступного времени');
            }
            return response.json();
        })
        .then(data => {
            timeSelect.disabled = false;
            timeSelect.innerHTML = '<option value="">Выберите время</option>';
            
            if (data.available_times.length === 0) {
                timeSelect.innerHTML = `<option value="">Нет доступного времени (рабочие часы: ${data.work_hours})</option>`;
                return;
            }
            
            data.available_times.forEach(time => {
                const option = document.createElement('option');
                option.value = time;
                option.textContent = time;
                timeSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            timeSelect.innerHTML = `<option value="">Ошибка: ${error.message}</option>`;
        });
}
        // Устанавливаем минимальную и максимальную даты
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            const maxDate = new Date();
            maxDate.setMonth(maxDate.getMonth() + 1);
            const maxDateStr = maxDate.toISOString().split('T')[0];
            
            document.getElementById('appointmentDate').min = today;
            document.getElementById('appointmentDate').max = maxDateStr;
            
            // Проверка рабочего дня при выборе даты
            document.getElementById('appointmentDate').addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const dayOfWeek = selectedDate.getDay(); // 0-воскресенье, 6-суббота
                
                if (dayOfWeek === 0 || dayOfWeek === 6) {
                    alert('Записи доступны только в будние дни (пн-пт).');
                    this.value = '';
                }
            });
        });

        // Автоматическое скрытие сообщения об успехе через 10 секунд
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.querySelector('.bg-green-100');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 10000);
    }
});
    </script>
</body>
</html>