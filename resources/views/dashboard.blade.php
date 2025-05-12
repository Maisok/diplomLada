<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Личный кабинет</title>
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
        .profile-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border-radius: 12px;
        }
        .info-label {
            color: #8b5f4d;
            font-weight: 500;
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
        .btn-danger {
            background-color: #e74c3c;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background-color: #c0392b;
        }
        .appointment-card {
            border-left: 3px solid #8b5f4d;
            transition: all 0.3s ease;
        }
        .appointment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] md:h-[400px] flex items-center justify-center">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Фон личного кабинета" class="absolute top-0 left-0 w-full h-full object-cover">
        <div class="relative z-20 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Личный кабинет</h1>
            <p class="text-white text-lg opacity-90">Добро пожаловать, {{ $user->name }}</p>
        </div>
    </section>

    <!-- Profile Content -->
    <section class="py-16 px-4">
        <div class="max-w-4xl mx-auto">
            <div class="profile-card bg-white p-8 md:p-12 mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-6">Ваши данные</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="info-label">Имя:</p>
                                <p class="text-lg">{{ $user->name }}</p>
                            </div>
                            <div>
                                <p class="info-label">Email:</p>
                                <p class="text-lg">{{ $user->email }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col justify-center space-y-4">
                        <form action="{{ route('profile.edit') }}" method="GET">
                            <button type="submit" class="btn-secondary w-full py-3 rounded-full font-medium">
                                Редактировать данные
                            </button>
                        </form>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-danger w-full text-white py-3 rounded-full font-medium">
                                Выйти из аккаунта
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Appointments Section -->
            <div class="profile-card bg-white p-8 md:p-12">
                <h2 class="text-2xl font-bold mb-8">Ваши записи</h2>
                
                @if($appointments->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500 mb-4">У вас пока нет записей</p>
                        <a href="{{ route('services') }}" class="btn-primary inline-block text-white px-6 py-2 rounded-full">
                            Записаться на прием
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($appointments as $appointment)
                        <div class="appointment-card bg-white p-6 rounded-lg border border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div>
                                        <p class="info-label">Услуга:</p>
                                        <p class="font-medium">{{ $appointment->service->name }}</p>
                                    </div>
                                    <div>
                                        <p class="info-label">Филиал:</p>
                                        <p>{{ $appointment->branch->name }}</p>
                                    </div>
                                    <div>
                                        <p class="info-label">Специалист:</p>
                                        <p>{{ $appointment->staff->first_name }} {{ $appointment->staff->last_name }}</p>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <p class="info-label">Дата и время:</p>
                                        <p>{{ $appointment->start_time->format('d.m.Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="info-label">Статус:</p>
                                        <p class="capitalize">
                                            @if($appointment->status == 'pending')
                                                <span class="text-yellow-600">Ожидает подтверждения</span>
                                            @elseif($appointment->status == 'active')
                                                <span class="text-green-600">Подтверждена</span>
                                            @elseif($appointment->status == 'completed')
                                                <span class="text-blue-600">Выполнена</span>
                                            @else
                                                <span class="text-red-600">Отменена</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex space-x-3 pt-2">
                                        @if($appointment->status == 'pending')
                                        <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-danger text-white px-4 py-2 rounded-full text-sm">
                                                Отменить запись
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Gift Certificates Section -->
    <div class="profile-card bg-white p-8 md:p-12 mt-8 max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Подарочные сертификаты</h2>
            <button onclick="openCertificateModal()" class="btn-primary text-white px-4 py-2 rounded-md">
                Купить сертификат
            </button>
        </div>
    
        @if(session('certificate_success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('certificate_success') }}
            </div>
        @endif
    
        @if($certificates->isEmpty())
            <p class="text-gray-500">У вас пока нет сертификатов</p>
        @else
            <div class="space-y-4">
                @foreach($certificates as $certificate)
                <div class="border border-gray-200 rounded-lg p-4 flex justify-between items-center">
                    <div>
                        <p class="font-medium">Сертификат #{{ $certificate->code }}</p>
                        <p class="text-[#8b5f4d]">{{ number_format($certificate->amount, 0, ',', ' ') }} ₽</p>
                        @if($certificate->recipient_name)
                            <p class="text-sm">Для: {{ $certificate->recipient_name }}</p>
                        @endif
                        <p class="text-sm text-gray-500">Действителен до: {{ $certificate->expires_at }}</p>
                    </div>
                    <a href="{{ route('gift-certificates.download', $certificate) }}" 
                       class="btn-secondary px-3 py-1 rounded-md text-sm">
                        Скачать
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Certificate Modal -->
    <div id="certificateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-8 max-w-md w-full">
            <h3 class="text-xl font-bold mb-4">Купить подарочный сертификат</h3>
            <form id="certificateForm" action="{{ route('gift-certificates.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Сумма (руб)</label>
                    <input type="number" id="amount" name="amount" 
                           min="{{ $certificateData['min_amount'] }}" 
                           max="{{ $certificateData['max_amount'] }}" 
                           step="500"
                           class="w-full px-4 py-2 border rounded-md" required>
                    <p class="text-sm text-gray-500 mt-1">
                        Минимум: {{ number_format($certificateData['min_amount'], 0, ',', ' ') }} руб. 
                        Максимум: {{ number_format($certificateData['max_amount'], 0, ',', ' ') }} руб.
                    </p>
                </div>
                <div class="mb-4">
                    <label for="recipient_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Имя получателя (необязательно, максимум {{ $certificateData['recipient_name_length'] }} символов)
                    </label>
                    <input type="text" id="recipient_name" name="recipient_name" 
                           maxlength="{{ $certificateData['recipient_name_length'] }}"
                           class="w-full px-4 py-2 border rounded-md">
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">
                        Сообщение (необязательно, максимум {{ $certificateData['message_max_length'] }} символов)
                    </label>
                    <textarea id="message" name="message" rows="3" 
                              maxlength="{{ $certificateData['message_max_length'] }}"
                              class="w-full px-4 py-2 border rounded-md"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="btn-secondary px-4 py-2 rounded-md">
                        Отмена
                    </button>
                    <button type="submit" class="btn-primary text-white px-4 py-2 rounded-md">
                        Купить
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-footer/>

    <script>
        // Константы из PHP
        const CERTIFICATE_MIN_AMOUNT = {{ $certificateData['min_amount'] }};
        const CERTIFICATE_MAX_AMOUNT = {{ $certificateData['max_amount'] }};
        const CERTIFICATE_NAME_LENGTH = {{ $certificateData['recipient_name_length'] }};
        const CERTIFICATE_MESSAGE_LENGTH = {{ $certificateData['message_max_length'] }};

        // Открытие модального окна
        function openCertificateModal() {
            document.getElementById('certificateModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // Закрытие модального окна
        function closeModal() {
            document.getElementById('certificateModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Валидация формы
        document.getElementById('certificateForm').addEventListener('submit', function(e) {
            const amountInput = document.getElementById('amount');
            const recipientNameInput = document.getElementById('recipient_name');
            const messageInput = document.getElementById('message');
            
            // Очистка предыдущих ошибок
            document.querySelectorAll('.error-message').forEach(el => el.remove());
            
            // Валидация суммы
            const amount = parseFloat(amountInput.value);
            if (isNaN(amount)) {
                showError(amountInput, 'Укажите сумму сертификата');
                e.preventDefault();
                return;
            }
            
            if (amount < CERTIFICATE_MIN_AMOUNT) {
                showError(amountInput, `Минимальная сумма: ${CERTIFICATE_MIN_AMOUNT.toLocaleString('ru-RU')} руб.`);
                e.preventDefault();
                return;
            }
            
            if (amount > CERTIFICATE_MAX_AMOUNT) {
                showError(amountInput, `Максимальная сумма: ${CERTIFICATE_MAX_AMOUNT.toLocaleString('ru-RU')} руб.`);
                e.preventDefault();
                return;
            }
            
            // Валидация имени получателя
            if (recipientNameInput.value.length > CERTIFICATE_NAME_LENGTH) {
                showError(recipientNameInput, `Максимальная длина: ${CERTIFICATE_NAME_LENGTH} символов`);
                e.preventDefault();
                return;
            }
            
            // Валидация сообщения
            if (messageInput.value.length > CERTIFICATE_MESSAGE_LENGTH) {
                showError(messageInput, `Максимальная длина: ${CERTIFICATE_MESSAGE_LENGTH} символов`);
                e.preventDefault();
                return;
            }
        });

        // Показ ошибки
        function showError(input, message) {
            const errorElement = document.createElement('div');
            errorElement.className = 'error-message text-red-500 text-sm mt-1';
            errorElement.textContent = message;
            input.parentNode.appendChild(errorElement);
            input.classList.add('border-red-500');
        }

        // Обработка ввода суммы
        document.getElementById('amount').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Ограничение максимальной суммы
            const value = parseInt(this.value) || 0;
            if (value > CERTIFICATE_MAX_AMOUNT) {
                this.value = CERTIFICATE_MAX_AMOUNT;
            }
            
            // Очистка ошибки
            if (this.classList.contains('border-red-500')) {
                this.classList.remove('border-red-500');
                const error = this.parentNode.querySelector('.error-message');
                if (error) error.remove();
            }
        });

        // Обработка ввода имени получателя
        document.getElementById('recipient_name').addEventListener('input', function() {
            // Ограничение длины
            if (this.value.length > CERTIFICATE_NAME_LENGTH) {
                this.value = this.value.substring(0, CERTIFICATE_NAME_LENGTH);
            }
            
            // Очистка ошибки
            if (this.classList.contains('border-red-500')) {
                this.classList.remove('border-red-500');
                const error = this.parentNode.querySelector('.error-message');
                if (error) error.remove();
            }
        });

        // Обработка ввода сообщения
        document.getElementById('message').addEventListener('input', function() {
            // Ограничение длины
            if (this.value.length > CERTIFICATE_MESSAGE_LENGTH) {
                this.value = this.value.substring(0, CERTIFICATE_MESSAGE_LENGTH);
            }
            
            // Очистка ошибки
            if (this.classList.contains('border-red-500')) {
                this.classList.remove('border-red-500');
                const error = this.parentNode.querySelector('.error-message');
                if (error) error.remove();
            }
        });
    </script>
</body>
</html>