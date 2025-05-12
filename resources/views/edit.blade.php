<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Редактирование профиля</title>
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
            background: linear-gradient(90deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.1) 100%);
        }
        .profile-form {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }
        .form-input {
            border-bottom: 1px solid #ddd;
            background: transparent;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            border-bottom-color: #8b5f4d;
        }
        .btn-primary {
            background-color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
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
        .input-label {
            color: #8b5f4d;
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Стили для модального окна */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            animation: modalFadeIn 0.3s;
        }
        
        @keyframes modalFadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
        }
        
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            background-color: #5a6268;
        }
        
        .delete-account-btn {
            color: #dc3545;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            margin-top: 2rem;
            display: block;
            text-align: center;
            width: 100%;
            padding: 0.5rem;
            transition: all 0.2s ease;
        }
        
        .delete-account-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] md:h-[400px] flex items-center justify-center">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Фон редактирования профиля" class="absolute top-0 left-0 w-full h-full object-cover">
        <div class="relative z-20 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Редактирование профиля</h1>
            <p class="text-white text-lg opacity-90">Обновите ваши данные</p>
        </div>
    </section>

    <!-- Profile Edit Form -->
    <section class="py-16 px-4">
        <div class="max-w-md mx-auto">
            <div class="profile-form bg-white p-8 md:p-12">
                <h2 class="text-2xl font-bold mb-8 text-center">Ваши данные</h2>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Name Input -->
                    <div>
                        <label class="input-label" for="name">Имя</label>
                        <input type="text" id="name" name="name" placeholder="Ваше имя" 
                               value="{{ old('name', $user->name) }}"
                               class="form-input w-full px-3 py-2 border rounded-md"
                               maxlength="50"
                               required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Email Input -->
                    <div>
                        <label class="input-label" for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Ваш email" 
                               value="{{ old('email', $user->email) }}"
                               class="form-input w-full px-3 py-2 border rounded-md"
                               maxlength="100"
                               required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Password Input -->
                    <div>
                        <label class="input-label" for="password">Новый пароль</label>
                        <input type="password" id="password" name="password" 
                               placeholder="Оставьте пустым, если не хотите менять" 
                               class="form-input w-full px-3 py-2 border rounded-md"
                               minlength="8"
                               maxlength="255">
                        <p class="text-sm text-gray-500 mt-1">
                            Пароль должен содержать минимум 8 символов, включая заглавные и строчные буквы и цифры
                        </p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password Input -->
                    <div>
                        <label class="input-label" for="password_confirmation">Подтвердите пароль</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" 
                               placeholder="Подтвердите новый пароль" 
                               class="form-input w-full px-3 py-2 border rounded-md"
                               minlength="8"
                               maxlength="255">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary w-full text-white py-3 rounded-full font-medium">
                        Сохранить изменения
                    </button>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('dashboard') }}" class="btn-secondary inline-block px-6 py-2 rounded-full font-medium">
                            Вернуться в профиль
                        </a>
                    </div>

                    <button type="button" onclick="openDeleteModal()" class="delete-account-btn">
                        Удалить аккаунт
                    </button>
                </form>

                @if(session('email_change'))
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 mt-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    {{ session('email_change.message') }}<br>
                                    <span class="font-medium">Текущий email:</span> {{ session('email_change.old_email') }}<br>
                                    <span class="font-medium">Новый email:</span> {{ session('email_change.new_email') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if($user->new_email)
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6 mt-4 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700">
                                Ваш email не подтвержден. Пожалуйста, проверьте почту <strong>{{ $user->new_email }}</strong> и подтвердите изменение.<br>
                                <a href="{{ route('profile.verify-email.resend') }}" class="font-medium text-amber-700 underline hover:text-amber-600">Отправить письмо повторно</a>
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Delete Account Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <h3 class="text-xl font-bold mb-4">Подтвердите удаление аккаунта</h3>
            <p>Вы уверены, что хотите удалить свой аккаунт? Это действие невозможно отменить. Все ваши данные будут безвозвратно удалены.</p>
            
            <form id="deleteAccountForm" action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                
                @if(empty($user->yandex_id))
                <div class="form-group mt-4">
                    <label for="confirmPassword" class="block mb-2">Введите ваш пароль для подтверждения:</label>
                    <input type="password" id="confirmPassword" name="password" required 
                           class="form-input w-full px-3 py-2 border rounded">
                </div>
                @else
                <input type="hidden" name="skip_password_check" value="1">
                <p class="mt-4 text-gray-600">Вы вошли через Яндекс, подтверждение пароля не требуется.</p>
                @endif
                
                <div class="modal-actions">
                    <button type="button" onclick="closeDeleteModal()" class="btn-cancel px-4 py-2 rounded-full">
                        Отмена
                    </button>
                    <button type="submit" class="btn-danger px-4 py-2 rounded-full">
                        Удалить аккаунт
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-footer/>

    <script>
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Обработка ввода имени
        document.getElementById('name').addEventListener('input', function() {
            // Ограничиваем ввод только буквами и пробелами
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
            
            // Ограничиваем длину до 50 символов
            if (this.value.length > 50) {
                this.value = this.value.substring(0, 50);
            }
            
            // Делаем первый символ заглавным
            this.value = capitalizeFirstLetter(this.value);
        });

        // Обработка ввода email
        document.getElementById('email').addEventListener('input', function() {
            // Ограничиваем ввод только символами, соответствующими формату email
            this.value = this.value.replace(/[^a-zA-Z0-9._@-]/g, '');
            
            // Ограничиваем длину до 100 символов
            if (this.value.length > 100) {
                this.value = this.value.substring(0, 100);
            }
        });

        // Обработка ввода пароля
        document.getElementById('password').addEventListener('input', function() {
            // Ограничиваем длину до 255 символов
            if (this.value.length > 255) {
                this.value = this.value.substring(0, 255);
            }
        });

        // Обработка подтверждения пароля
        document.getElementById('password_confirmation').addEventListener('input', function() {
            // Ограничиваем длину до 255 символов
            if (this.value.length > 255) {
                this.value = this.value.substring(0, 255);
            }
        });

        // Функции для модального окна
        function openDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
        }
        
        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
        
        // Закрытие модального окна при клике вне его
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target == modal) {
                closeDeleteModal();
            }
        }
        
        // Обработка отправки формы удаления
        document.getElementById('deleteAccountForm').addEventListener('submit', function(e) {
            @if(empty($user->yandex_id))
            const password = document.getElementById('confirmPassword').value;
            if (!password) {
                alert('Пожалуйста, введите ваш пароль для подтверждения');
                e.preventDefault();
                return;
            }
            @endif
            
        });
    </script>
</body>
</html>