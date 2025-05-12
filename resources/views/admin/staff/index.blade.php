<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Управление сотрудниками</title>
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
            background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
        }
        .staff-card {
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .staff-card:hover {
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
        .btn-danger {
            background-color: #e53e3e;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c53030;
        }
        .form-input {
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-color: #8b5f4d;
            box-shadow: 0 0 0 2px rgba(139, 95, 77, 0.2);
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
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Управление сотрудниками" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex items-center justify-center text-center px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-white">Управление сотрудниками</h1>
        </div>
    </section>

    <!-- Main Content -->
    <main class="py-12 px-4 max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 md:p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <h2 class="text-2xl font-bold mb-4 md:mb-0">Список сотрудников</h2>
                <button onclick="openModal('create-modal')" class="btn-primary text-white px-6 py-3 rounded-full font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Добавить сотрудника
                </button>
            </div>

            <!-- Staff Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($staff as $employee)
                <div class="staff-card bg-white rounded-lg overflow-hidden relative">
                    <div class="absolute top-4 right-4 dropdown">
                        <button class="text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </button>
                        <div class="dropdown-content">
                            <div onclick="openEditModal({{ $employee->id }})" class="dropdown-item flex items-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Редактировать
                            </div>
                            <form id="delete-form-{{ $employee->id }}" action="{{ route('admin.staff.destroy', $employee) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div onclick="confirmDelete({{ $employee->id }})" class="dropdown-item flex items-center text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Удалить
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            @if($employee->image)
                            <img src="{{ asset($employee->image) }}" alt="{{ $employee->full_name }}" class="w-16 h-16 rounded-full object-cover mr-4">
                            @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            @endif
                            <div>
                                <h3 class="text-xl font-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                                <p class="text-gray-600">{{ $employee->position }}</p>
                                <p class="text-sm text-gray-500 mt-1">Логин: {{ $employee->login }}</p>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <div class="flex items-start text-gray-600 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>{{ $employee->branch->name ?? 'Не указан' }}</span>
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
                    <h3 class="text-xl font-semibold">Добавить сотрудника</h3>
                    <button onclick="closeModal('create-modal')" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="create-form" action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="create-first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                            <input type="text" id="create-first_name" name="first_name" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="create-last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия</label>
                            <input type="text" id="create-last_name" name="last_name" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="create-position" class="block text-sm font-medium text-gray-700 mb-1">Должность</label>
                            <input type="text" id="create-position" name="position" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="create-branch_id" class="block text-sm font-medium text-gray-700 mb-1">Филиал</label>
                            <select id="create-branch_id" name="branch_id" class="form-input w-full px-3 py-2 rounded-md" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="create-login" class="block text-sm font-medium text-gray-700 mb-1">Логин (5 цифр)</label>
                            <input type="text" id="create-login" name="login" class="form-input w-full px-3 py-2 rounded-md" required 
                                   pattern="\d{5}" title="Логин должен состоять из 5 цифр" maxlength="5">
                        </div>
                        <div class="relative">
                            <label for="create-password" class="block text-sm font-medium text-gray-700 mb-1">Пароль (минимум 8 символов)</label>
                            <input type="password" id="create-password" name="password" class="form-input w-full px-3 py-2 rounded-md" required minlength="8">
                            <span class="password-toggle" onclick="togglePassword('create-password')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                        </div>
                        <div class="relative">
                            <label for="create-password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Подтверждение пароля</label>
                            <input type="password" id="create-password_confirmation" name="password_confirmation" class="form-input w-full px-3 py-2 rounded-md" required minlength="8">
                            <span class="password-toggle" onclick="togglePassword('create-password_confirmation')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                        </div>
                        <div>
                            <label for="create-image" class="block text-sm font-medium text-gray-700 mb-1">Фотография</label>
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
                    <h3 class="text-xl font-semibold">Редактировать сотрудника</h3>
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
                            <label for="edit-first_name" class="block text-sm font-medium text-gray-700 mb-1">Имя</label>
                            <input type="text" id="edit-first_name" name="first_name" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="edit-last_name" class="block text-sm font-medium text-gray-700 mb-1">Фамилия</label>
                            <input type="text" id="edit-last_name" name="last_name" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="edit-position" class="block text-sm font-medium text-gray-700 mb-1">Должность</label>
                            <input type="text" id="edit-position" name="position" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="edit-branch_id" class="block text-sm font-medium text-gray-700 mb-1">Филиал</label>
                            <select id="edit-branch_id" name="branch_id" class="form-input w-full px-3 py-2 rounded-md" required>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="edit-login" class="block text-sm font-medium text-gray-700 mb-1">Логин (5 цифр)</label>
                            <input type="text" id="edit-login" name="login" class="form-input w-full px-3 py-2 rounded-md" required 
                                   pattern="\d{5}" title="Логин должен состоять из 5 цифр" maxlength="5">
                        </div>
                        <div class="relative">
                            <label for="edit-password" class="block text-sm font-medium text-gray-700 mb-1">Новый пароль (оставьте пустым, если не меняется)</label>
                            <input type="password" id="edit-password" name="password" class="form-input w-full px-3 py-2 rounded-md" minlength="8">
                            <span class="password-toggle" onclick="togglePassword('edit-password')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                        </div>
                        <div class="relative">
                            <label for="edit-password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Подтверждение пароля</label>
                            <input type="password" id="edit-password_confirmation" name="password_confirmation" class="form-input w-full px-3 py-2 rounded-md" minlength="8">
                            <span class="password-toggle" onclick="togglePassword('edit-password_confirmation')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </span>
                        </div>
                        <div>
                            <label for="edit-image" class="block text-sm font-medium text-gray-700 mb-1">Фотография</label>
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

        // Edit modal with data
        function openEditModal(staffId) {
            fetch(`/admin/staff/${staffId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-first_name').value = data.first_name;
                    document.getElementById('edit-last_name').value = data.last_name;
                    document.getElementById('edit-position').value = data.position;
                    document.getElementById('edit-branch_id').value = data.branch_id;
                    document.getElementById('edit-login').value = data.login;
                    
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
                    document.getElementById('edit-form').action = `/admin/staff/${staffId}`;
                    
                    openModal('edit-modal');
                });
        }

        // Confirm delete
        function confirmDelete(staffId) {
            if (confirm('Вы уверены, что хотите удалить этого сотрудника?')) {
                document.getElementById(`delete-form-${staffId}`).submit();
            }
        }

        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            if (input.type === 'password') {
                input.type = 'text';
            } else {
                input.type = 'password';
            }
        }

       // Форматирование ввода для имен
        function formatNameInput(input) {
            input.value = input.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
            if (input.value.length > 50) {
                input.value = input.value.slice(0, 50);
            }
        }

        // Форматирование ввода для должности
        function formatPositionInput(input) {
            if (input.value.length > 50) {
                input.value = input.value.slice(0, 50);
            }
        }

        // Форматирование логина (только цифры, ровно 5)
        function formatLoginInput(input) {
            input.value = input.value.replace(/\D/g, '');
            if (input.value.length > 5) {
                input.value = input.value.slice(0, 5);
            }
        }

        // Применение форматирования при вводе
        document.querySelectorAll('#create-first_name, #create-last_name, #edit-first_name, #edit-last_name').forEach(input => {
            input.addEventListener('input', function() {
                formatNameInput(this);
            });
        });

        document.querySelectorAll('#create-position, #edit-position').forEach(input => {
            input.addEventListener('input', function() {
                formatPositionInput(this);
            });
        });

        document.querySelectorAll('#create-login, #edit-login').forEach(input => {
            input.addEventListener('input', function() {
                formatLoginInput(this);
            });
        });

        // Валидация форм перед отправкой
        document.getElementById('create-form').addEventListener('submit', function(e) {
            const firstName = document.getElementById('create-first_name');
            const lastName = document.getElementById('create-last_name');
            const position = document.getElementById('create-position');
            const login = document.getElementById('create-login');
            const password = document.getElementById('create-password');
            const passwordConfirmation = document.getElementById('create-password_confirmation');

            // Проверка длины полей
            if (firstName.value.length > 50 || lastName.value.length > 50 || position.value.length > 50) {
                e.preventDefault();
                alert('Максимальная длина для имени, фамилии и должности - 50 символов');
                return;
            }

            if (!/^\d{5}$/.test(login.value)) {
                e.preventDefault();
                alert('Логин должен состоять из 5 цифр');
                return;
            }

            if (password.value.length < 8) {
                e.preventDefault();
                alert('Пароль должен содержать минимум 8 символов');
                return;
            }

            if (password.value !== passwordConfirmation.value) {
                e.preventDefault();
                alert('Пароли не совпадают');
                return;
            }
        });

        document.getElementById('edit-form').addEventListener('submit', function(e) {
            const firstName = document.getElementById('edit-first_name');
            const lastName = document.getElementById('edit-last_name');
            const position = document.getElementById('edit-position');
            const login = document.getElementById('edit-login');
            const password = document.getElementById('edit-password');
            const passwordConfirmation = document.getElementById('edit-password_confirmation');

            // Проверка длины полей
            if (firstName.value.length > 50 || lastName.value.length > 50 || position.value.length > 50) {
                e.preventDefault();
                alert('Максимальная длина для имени, фамилии и должности - 50 символов');
                return;
            }

            if (!/^\d{5}$/.test(login.value)) {
                e.preventDefault();
                alert('Логин должен состоять из 5 цифр');
                return;
            }

            if (password.value && password.value.length < 8) {
                e.preventDefault();
                alert('Пароль должен содержать минимум 8 символов');
                return;
            }

            if (password.value !== passwordConfirmation.value) {
                e.preventDefault();
                alert('Пароли не совпадают');
                return;
            }
        });
    </script>
</body>
</html>