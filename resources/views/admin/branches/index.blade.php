
@use('App\Models\Branch')
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Управление филиалами</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        .branch-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .branch-card:hover {
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
        .staff-badge {
            background-color: #8b5f4d;
            color: white;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
            display: inline-block;
        }
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            min-height: 42px;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            z-index: 1;
            border-radius: 8px;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .dropdown-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .dropdown-item:hover {
            background-color: #f5f5f5;
        }
        .modal {
            transition: opacity 0.3s ease;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Управление филиалами" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex items-center justify-center text-center px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-white">Управление филиалами</h1>
        </div>
    </section>

    <!-- Main Content -->
    <main class="py-12 px-4 max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 md:p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <h2 class="text-2xl font-bold mb-4 md:mb-0">Список филиалов</h2>
                <button onclick="openModal('create-modal')" class="btn-primary text-white px-6 py-3 rounded-full font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Добавить филиал
                </button>
            </div>

            <!-- Вывод ошибок валидации -->
<!-- Вывод ошибок валидации -->
@if ($errors->any())
<div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-red-700">Ошибки при заполнении формы:</h3>
            <div class="mt-2 text-sm text-red-600">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Вывод успешных сообщений -->
@if(session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif
            <!-- Branches Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($branches as $branch)
                <div class="branch-card bg-white rounded-lg overflow-hidden relative">
                    <div class="absolute top-4 right-4 dropdown">
                        <button class="text-gray-500 hover:text-gray-700 bg-white bg-opacity-80 rounded-full p-1 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </button>
                        <div class="dropdown-content">
                            <div onclick="openEditModal({{ $branch->id }})" class="dropdown-item flex items-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Редактировать
                            </div>
                            @if($branch->staff->count() > 0)
                            <div class="dropdown-item flex items-center text-gray-400 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Удалить (есть сотрудники)
                            </div>
                            @else
                            <form id="delete-form-{{ $branch->id }}" action="{{ route('admin.branches.destroy', $branch) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div onclick="confirmDelete({{ $branch->id }})" class="dropdown-item flex items-center text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Удалить
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>

                    @if($branch->image)
                    <img src="{{ asset($branch->image) }}" alt="{{ $branch->name }}" class="w-full h-48 object-cover">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif

                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $branch->name }}</h3>
                        <div class="space-y-2 text-gray-600 mb-4">
                            <p class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $branch->address }}
                            </p>
                            <p class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $branch->phone }}
                            </p>
                            <p class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                {{ $branch->email }}
                            </p>
                            <p class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $branch->work_time_start->format('H:i') }} - {{ $branch->work_time_end->format('H:i') }}
                            </p>
                        </div>


                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Статус:</h4>
                            <div>
                                @if($branch->is_active)
                                <span class="text-gray-400 text-sm">Открыто</span>
                                @else
                                <span class="text-gray-400 text-sm">Закрыто</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Сотрудники:</h4>
                            <div>
                                @foreach($branch->staff as $staff)
                                <span class="staff-badge">{{ $staff->first_name }} {{ $staff->last_name }}</span>
                                @endforeach
                                @if($branch->staff->count() == 0)
                                <span class="text-gray-400 text-sm">Нет прикрепленных сотрудников</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </main>

    <!-- Create Modal -->
    <div id="create-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 invisible">
        <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Добавить филиал</h3>
                    <button onclick="closeModal('create-modal')" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="create-form" action="{{ route('admin.branches.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="create-name" class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                            <input type="text" id="create-name" name="name" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="create-address" class="block text-sm font-medium text-gray-700 mb-1">Адрес</label>
                            <textarea id="create-address" name="address" class="form-input w-full px-3 py-2 rounded-md" required></textarea>
                        </div>
                        <div>
                            <label for="create-phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                            <input type="text" id="create-phone" name="phone" 
                            class="form-input w-full px-3 py-2 rounded-md" 
                            pattern="8\s\d{3}\s\d{3}\s\d{2}\s\d{2}"
                            placeholder="8 888 888 88 88"
                            required>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Статус</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1" {{ old('is_active', $service->is_active ?? true) ? 'selected' : '' }}>Активный</option>
                                <option value="0" {{ old('is_active', $service->is_active ?? false) ? 'selected' : '' }}>Неактивный</option>
                            </select>
                        </div>
                        <div>
                            <label for="create-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="create-email" name="email" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="create-work_time_start" class="block text-sm font-medium text-gray-700 mb-1">Начало работы</label>
                                <input type="time" id="create-work_time_start" name="work_time_start" 
                                       class="form-input w-full px-3 py-2 rounded-md" 
                                       min="00:00" max="23:59" step="3600" required>
                            </div>
                            <div>
                                <label for="create-work_time_end" class="block text-sm font-medium text-gray-700 mb-1">Конец работы</label>
                                <input type="time" id="create-work_time_end" name="work_time_end" 
                                       class="form-input w-full px-3 py-2 rounded-md" 
                                       min="00:00" max="23:59" step="3600" required>
                            </div>
                        </div>
                        <div>
                            <label for="create-image" class="block text-sm font-medium text-gray-700 mb-1">Изображение</label>
                            <input type="file" id="create-image" name="image" class="form-input w-full px-3 py-2 rounded-md">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('create-modal')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Отмена</button>
                        <button type="submit" class="btn-primary px-4 py-2 text-white rounded-md">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 opacity-0 invisible">
        <div class="bg-white rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold">Редактировать филиал</h3>
                    <button onclick="closeModal('edit-modal')" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="edit-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label for="edit-name" class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                            <input type="text" id="edit-name" name="name" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="edit-address" class="block text-sm font-medium text-gray-700 mb-1">Адрес</label>
                            <textarea id="edit-address" name="address" class="form-input w-full px-3 py-2 rounded-md"  placeholder="г. Москва, ул. Примерная, 123" required></textarea>
                        </div>
                        <div>
                            <label for="edit-phone" class="block text-sm font-medium text-gray-700 mb-1">Телефон</label>
                            <input type="text" id="edit-phone" name="phone" 
                            class="form-input w-full px-3 py-2 rounded-md" 
                            pattern="8\s\d{3}\s\d{3}\s\d{2}\s\d{2}"
                            placeholder="8 888 888 88 88"
                            required>
                        </div>
                        <div class="form-group">
                            <label for="is_active">Статус</label>
                            <select name="is_active" id="is_active" class="form-control">
                                <option value="1" {{ old('is_active', $branch->is_active ?? true) ? 'selected' : '' }}>Активный</option>
                                <option value="0" {{ old('is_active', $branch->is_active ?? false) ? 'selected' : '' }}>Неактивный</option>
                            </select>
                        </div>
                        <div>
                            <label for="edit-email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="edit-email" name="email" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="edit-work_time_start" class="block text-sm font-medium text-gray-700 mb-1">Начало работы</label>
                                <input type="time" id="edit-work_time_start" name="work_time_start" 
                                       class="form-input w-full px-3 py-2 rounded-md" 
                                       min="00:00" max="23:59" step="3600" required>
                            </div>
                            <div>
                                <label for="edit-work_time_end" class="block text-sm font-medium text-gray-700 mb-1">Конец работы</label>
                                <input type="time" id="edit-work_time_end" name="work_time_end" 
                                       class="form-input w-full px-3 py-2 rounded-md" 
                                       min="00:00" max="23:59" step="3600" required>
                            </div>
                        </div>
                        <div>
                            <label for="edit-image" class="block text-sm font-medium text-gray-700 mb-1">Изображение</label>
                            <input type="file" id="edit-image" name="image" class="form-input w-full px-3 py-2 rounded-md">
                            <div id="current-image" class="mt-2"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('edit-modal')" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">Отмена</button>
                        <button type="submit" class="btn-primary px-4 py-2 text-white rounded-md">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // Modal functions
        function openModal(id) {
            document.getElementById(id).classList.remove('invisible', 'opacity-0');
            document.body.classList.add('overflow-hidden');
        }
    
        function closeModal(id) {
            document.getElementById(id).classList.add('invisible', 'opacity-0');
            document.body.classList.remove('overflow-hidden');
        }
    
        // Input validation and formatting
        function validateBranchInput(input, fieldType) {
            switch(fieldType) {
                case 'name':
                    // Ограничение длины названия
                    if (input.value.length > {{ Branch::MAX_NAME_LENGTH }}) {
                        input.value = input.value.slice(0, {{ Branch::MAX_NAME_LENGTH }});
                        alert('Название не должно превышать {{ Branch::MAX_NAME_LENGTH }} символов');
                    }
                    break;
                
            case 'address':
                // Проверка формата адреса
                const addressPattern = /^г\.[а-яА-ЯёЁ\s-]+,\sул\.[а-яА-ЯёЁ\s-]+,\s\d+[а-яА-ЯёЁ\/]*$/u;
                if (!addressPattern.test(addressInput.value)) {
                    alert('Адрес должен быть в формате: "г. Москва, ул. Примерная, 123" или "г. Москва, ул. Примерная, 123а" или "г. Москва, ул. Примерная, 123/2"');
                    return false;
                }
                // Ограничение длины адреса
                if (input.value.length > {{ Branch::MAX_ADDRESS_LENGTH }}) {
                    input.value = input.value.slice(0, {{ Branch::MAX_ADDRESS_LENGTH }});
                    alert('Адрес не должен превышать {{ Branch::MAX_ADDRESS_LENGTH }} символов');
                }
                break;
                    
                    case 'phone':
    // Удаляем все нецифровые символы
    let phone = input.value.replace(/\D/g, '');
    
    // Гарантируем, что номер начинается с 8
    if (phone.length > 0 && phone.charAt(0) !== '8') {
        phone = '8' + phone.substring(1);
    }
    
    // Ограничиваем длину (11 цифр: 8 + 10 цифр номера)
    if (phone.length > 11) {
        phone = phone.substring(0, 11);
    }
    
    // Форматируем номер
    let formattedPhone = '';
    if (phone.length > 0) {
        formattedPhone = phone.charAt(0); // 8
        if (phone.length > 1) {
            formattedPhone += ' ' + phone.substring(1, 4); // 888
        }
        if (phone.length > 4) {
            formattedPhone += ' ' + phone.substring(4, 7); // 888
        }
        if (phone.length > 7) {
            formattedPhone += ' ' + phone.substring(7, 9); // 88
        }
        if (phone.length > 9) {
            formattedPhone += ' ' + phone.substring(9, 11); // 88
        }
    }
    
    input.value = formattedPhone;
    break;
                                        
                case 'email':
                    // Ограничение длины email
                    if (input.value.length > {{ Branch::MAX_EMAIL_LENGTH }}) {
                        input.value = input.value.slice(0, {{ Branch::MAX_EMAIL_LENGTH }});
                        alert('Email не должен превышать {{ Branch::MAX_EMAIL_LENGTH }} символов');
                    }
                    break;
            }
        }
    
        // Form validation before submission
        function validateBranchForm(formId) {
            const form = document.getElementById(formId);
            const nameInput = form.querySelector('input[name="name"]');
            const addressInput = form.querySelector('textarea[name="address"]');
            const phoneInput = form.querySelector('input[name="phone"]');
            const emailInput = form.querySelector('input[name="email"]');
            const startTimeInput = form.querySelector('input[name="work_time_start"]');
            const endTimeInput = form.querySelector('input[name="work_time_end"]');
            const imageInput = form.querySelector('input[name="image"]');
            
            // Validate name
            if (!nameInput.value.trim()) {
                alert('Название филиала обязательно для заполнения');
                return false;
            }
            
            if (nameInput.value.length > {{ Branch::MAX_NAME_LENGTH }}) {
                alert('Название не должно превышать {{ Branch::MAX_NAME_LENGTH }} символов');
                return false;
            }
            
            // Validate address
            if (!addressInput.value.trim()) {
                alert('Адрес обязателен для заполнения');
                return false;
            }
            
            if (addressInput.value.length > {{ Branch::MAX_ADDRESS_LENGTH }}) {
                alert('Адрес не должен превышать {{ Branch::MAX_ADDRESS_LENGTH }} символов');
                return false;
            }
            
            // Validate phone
            if (!phoneInput.value.trim()) {
                alert('Телефон обязателен для заполнения');
                return false;
            }
            
            if (phoneInput.value.length > {{ Branch::MAX_PHONE_LENGTH }}) {
                alert('Телефон не должен превышать {{ Branch::MAX_PHONE_LENGTH }} символов');
                return false;
            }
            
            if (!/^[\+\d\(\)\-\s]+$/.test(phoneInput.value)) {
                alert('Телефон должен содержать только цифры, пробелы, скобки и знак +');
                return false;
            }
            
            // Validate email
            if (!emailInput.value.trim()) {
                alert('Email обязателен для заполнения');
                return false;
            }
            
            const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailPattern.test(emailInput.value)) {
                alert('Введите корректный email адрес');
                return false;
            }
            
            if (emailInput.value.length > {{ Branch::MAX_EMAIL_LENGTH }}) {
                alert('Email не должен превышать {{ Branch::MAX_EMAIL_LENGTH }} символов');
                return false;
            }
            
            // Validate work time
            if (!startTimeInput.value || !endTimeInput.value) {
                alert('Укажите время работы филиала');
                return false;
            }
            
            if (startTimeInput.value >= endTimeInput.value) {
                alert('Время окончания работы должно быть позже времени начала');
                return false;
            }
            
            // Validate image if exists
            if (imageInput.files.length > 0) {
                const file = imageInput.files[0];
                const maxSize = {{ Branch::MAX_IMAGE_SIZE_KB }} * 1024;
                
                if (file.size > maxSize) {
                    alert(`Размер изображения не должен превышать ${maxSize / 1024}MB`);
                    return false;
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Допустимые форматы изображений: jpeg, png, jpg, gif, svg');
                    return false;
                }
            }
            
            return true;
        }
    
        // Edit modal with data
        function openEditModal(branchId) {
            fetch(`/admin/branches/${branchId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-name').value = data.name || '';
                    document.getElementById('edit-address').value = data.address || '';
                    document.getElementById('edit-phone').value = data.phone || '';
                    document.getElementById('edit-email').value = data.email || '';
                    document.getElementById('edit-work_time_start').value = data.work_time_start || '';
                    document.getElementById('edit-work_time_end').value = data.work_time_end || '';
                    
                    // Set current image if exists
                    const currentImageDiv = document.getElementById('current-image');
                    if (data.image) {
                        currentImageDiv.innerHTML = `
                            <p class="text-sm text-gray-500 mb-1">Текущее изображение:</p>
                            <img src="${data.image}" alt="Current Image" class="w-32 h-32 object-cover rounded-md">
                        `;
                    } else {
                        currentImageDiv.innerHTML = '<p class="text-sm text-gray-500">Нет текущего изображения</p>';
                    }
                    
                    // Update form action
                    document.getElementById('edit-form').action = `/admin/branches/${branchId}`;
                    
                    openModal('edit-modal');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при загрузке данных филиала');
                });
        }
    
        // Confirm delete
        function confirmDelete(branchId) {
            if (confirm('Вы уверены, что хотите удалить этот филиал?')) {
                document.getElementById(`delete-form-${branchId}`).submit();
            }
        }
    
        // Attach event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Name input validation
            document.getElementById('create-name')?.addEventListener('input', function() {
                validateBranchInput(this, 'name');
            });
            
            document.getElementById('edit-name')?.addEventListener('input', function() {
                validateBranchInput(this, 'name');
            });
            
            // Address input validation
            document.getElementById('create-address')?.addEventListener('input', function() {
                validateBranchInput(this, 'address');
            });
            
            document.getElementById('edit-address')?.addEventListener('input', function() {
                validateBranchInput(this, 'address');
            });
            
            // Phone input validation
            document.getElementById('create-phone')?.addEventListener('input', function() {
                validateBranchInput(this, 'phone');
            });
            
            document.getElementById('edit-phone')?.addEventListener('input', function() {
                validateBranchInput(this, 'phone');
            });
            
            // Email input validation
            document.getElementById('create-email')?.addEventListener('input', function() {
                validateBranchInput(this, 'email');
            });
            
            document.getElementById('edit-email')?.addEventListener('input', function() {
                validateBranchInput(this, 'email');
            });
            
            // Form submission validation
            document.getElementById('create-form')?.addEventListener('submit', function(e) {
                if (!validateBranchForm('create-form')) {
                    e.preventDefault();
                }
            });
            
            document.getElementById('edit-form')?.addEventListener('submit', function(e) {
                if (!validateBranchForm('edit-form')) {
                    e.preventDefault();
                }
            });




                
                document.querySelectorAll('input[name="phone"]').forEach(input => {
                    input.addEventListener('input', function(e) {
                        // Получаем позицию курсора
                        const cursorPosition = e.target.selectionStart;
                        
                        // Удаляем все нецифровые символы
                        let phone = this.value.replace(/\D/g, '');
                        
                        // Ограничиваем длину
                        if (phone.length > 11) {
                            phone = phone.substring(0, 11);
                        }
                        
                        // Форматируем номер
                        let formattedPhone = '';
                        if (phone.length > 0) {
                            formattedPhone = phone.charAt(0); // 8
                            if (phone.length > 1) {
                                formattedPhone += ' ' + phone.substring(1, 4); // 888
                            }
                            if (phone.length > 4) {
                                formattedPhone += ' ' + phone.substring(4, 7); // 888
                            }
                            if (phone.length > 7) {
                                formattedPhone += ' ' + phone.substring(7, 9); // 88
                            }
                            if (phone.length > 9) {
                                formattedPhone += ' ' + phone.substring(9, 11); // 88
                            }
                        }
                        
                        // Устанавливаем отформатированное значение
                        this.value = formattedPhone;
                        
                        // Восстанавливаем позицию курсора
                        // Увеличиваем позицию на количество добавленных пробелов
                        let addedSpaces = (this.value.match(/\s/g) || []).length;
                        e.target.setSelectionRange(cursorPosition + addedSpaces, cursorPosition + addedSpaces);
                    });
                });
        });
    
        // AJAX form submission for edit
        document.getElementById('edit-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            
            submitButton.disabled = true;
            submitButton.innerHTML = 'Сохранение...';
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.reload();
                } else {
                    showErrors(data.errors || {message: data.message});
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при сохранении');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Сохранить';
            });
        });
    
        function showErrors(errors) {
            // Очистка предыдущих ошибок
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            
            // Показ новых ошибок
            for (const [field, messages] of Object.entries(errors)) {
                const input = document.getElementById(`edit-${field}`);
                if (input) {
                    const errorContainer = document.createElement('div');
                    errorContainer.className = 'error-message text-red-500 text-sm mt-1';
                    errorContainer.textContent = Array.isArray(messages) ? messages.join(' ') : messages;
                    input.parentNode.appendChild(errorContainer);
                }
            }
        }
    </script>
</body>
</html>