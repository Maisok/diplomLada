<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Управление услугами</title>
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
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            min-height: 42px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #8b5f4d;
            border: none;
            color: white;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Управление услугами" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex items-center justify-center text-center px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-white">Управление услугами</h1>
        </div>
    </section>

    <!-- Main Content -->
    <main class="py-12 px-4 max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-6 md:p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <h2 class="text-2xl font-bold mb-4 md:mb-0">Список услуг</h2>
                <button onclick="openModal('create-modal')" class="btn-primary text-white px-6 py-3 rounded-full font-medium flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Добавить услугу
                </button>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($services as $service)
                <div class="service-card bg-white rounded-lg overflow-hidden relative">
                    <div class="absolute top-4 right-4 dropdown">
                        <button class="text-gray-500 hover:text-gray-700 bg-white bg-opacity-80 rounded-full p-1 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                            </svg>
                        </button>
                        <div class="dropdown-content">
                            <div onclick="openEditModal({{ $service->id }})" class="dropdown-item flex items-center text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Редактировать
                            </div>
                            @if($service->staff()->count() > 0)
                            <div class="dropdown-item flex items-center text-gray-400 cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Удалить (есть сотрудники)
                            </div>
                            @else
                            <form id="delete-form-{{ $service->id }}" action="{{ route('admin.services.destroy', $service) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div onclick="confirmDelete({{ $service->id }})" class="dropdown-item flex items-center text-red-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Удалить
                                </div>
                            </form>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        @if($service->image)
                        <div class="mb-4 rounded-lg overflow-hidden">
                            <img src="{{ asset($service->image) }}" alt="{{ $service->name }}" class="w-full h-48 object-cover">
                        </div>
                        @else
                        <div class="w-full h-48 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif

                        <div class="mb-4">
                            <h3 class="text-xl font-semibold">{{ $service->name }}</h3>
                            <p class="text-gray-600">{{ $service->price }} ₽</p>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Прикрепленные сотрудники:</h4>
                            <div>
                                @foreach($service->staff as $staff)
                                <span class="staff-badge">{{ $staff->first_name }} {{ $staff->last_name }}</span>
                                @endforeach
                                @if($service->staff->count() == 0)
                                <span class="text-sm text-gray-400">Нет прикрепленных сотрудников</span>
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
                    <h3 class="text-xl font-semibold">Добавить услугу</h3>
                    <button onclick="closeModal('create-modal')" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="create-form" action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="create-name" class="block text-sm font-medium text-gray-700 mb-1">Название</label>
                            <input type="text" id="create-name" name="name" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="create-price" class="block text-sm font-medium text-gray-700 mb-1">Цена (₽)</label>
                            <input type="text" id="create-price" name="price" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="create-staff" class="block text-sm font-medium text-gray-700 mb-1">Сотрудники</label>
                            <select id="create-staff" name="staff[]" multiple class="form-input w-full px-3 py-2 rounded-md">
                                @foreach($allStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                                @endforeach
                            </select>
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
                    <h3 class="text-xl font-semibold">Редактировать услугу</h3>
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
                            <label for="edit-price" class="block text-sm font-medium text-gray-700 mb-1">Цена (₽)</label>
                            <input type="text" id="edit-price" name="price" class="form-input w-full px-3 py-2 rounded-md" required>
                        </div>
                        <div>
                            <label for="edit-staff" class="block text-sm font-medium text-gray-700 mb-1">Сотрудники</label>
                            <select id="edit-staff" name="staff[]" multiple class="form-input w-full px-3 py-2 rounded-md">
                                @foreach($allStaff as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                                @endforeach
                            </select>
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
        // Initialize select2
        $(document).ready(function() {
            $('#create-staff, #edit-staff').select2({
                placeholder: "Выберите сотрудников",
                width: '100%'
            });
        });
    
        // Input validation and formatting
        function validateServiceInput(input, isPrice = false) {
            if (isPrice) {
                // Format price input (allow only numbers and dot)
                input.value = input.value.replace(/[^\d.]/g, '');
                
                // Ensure only one dot and max 2 decimal places
                const parts = input.value.split('.');
                if (parts.length > 2) {
                    input.value = parts[0] + '.' + parts.slice(1).join('');
                } else if (parts.length === 2) {
                    input.value = parts[0] + '.' + parts[1].slice(0, 2);
                }
                
                // Validate max value
                const value = parseFloat(input.value);
                if (value > 99999999.99) {
                    input.value = '99999999.99';
                    alert('Максимальная цена не может превышать 99,999,999.99');
                }
            } else {
                // Format name input (allow only letters, spaces and hyphens)
                input.value = input.value.replace(/[^a-zA-Zа-яА-Я\s\-]/g, '');
                
                // Validate max length
                if (input.value.length > 50) {
                    input.value = input.value.slice(0, 50);
                    alert('Название не должно превышать 50 символов');
                }
            }
        }
    
        // Form validation before submission
        function validateServiceForm(formId) {
            const form = document.getElementById(formId);
            const nameInput = form.querySelector('input[name="name"]');
            const priceInput = form.querySelector('input[name="price"]');
            
            // Validate name
            if (!nameInput.value.trim()) {
                alert('Название услуги обязательно для заполнения');
                return false;
            }
            
            if (nameInput.value.length > 50) {
                alert('Название не должно превышать 50 символов');
                return false;
            }
            
            if (!/^[a-zA-Zа-яА-Я\s\-]+$/.test(nameInput.value)) {
                alert('Название может содержать только буквы, пробелы и дефисы');
                return false;
            }
            
            // Validate price
            if (!priceInput.value.trim()) {
                alert('Цена обязательна для заполнения');
                return false;
            }
            
            const price = parseFloat(priceInput.value);
            if (isNaN(price)) {
                alert('Цена должна быть числом');
                return false;
            }
            
            if (price < 0) {
                alert('Цена не может быть отрицательной');
                return false;
            }
            
            if (price > 99999999.99) {
                alert('Цена не может превышать 99,999,999.99');
                return false;
            }
            
            return true;
        }
    
        // Attach event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Name input validation
            document.getElementById('create-name')?.addEventListener('input', function() {
                validateServiceInput(this);
            });
            
            document.getElementById('edit-name')?.addEventListener('input', function() {
                validateServiceInput(this);
            });
            
            // Price input validation
            document.getElementById('create-price')?.addEventListener('input', function() {
                validateServiceInput(this, true);
            });
            
            document.getElementById('edit-price')?.addEventListener('input', function() {
                validateServiceInput(this, true);
            });
            
            // Form submission validation
            document.getElementById('create-form')?.addEventListener('submit', function(e) {
                if (!validateServiceForm('create-form')) {
                    e.preventDefault();
                }
            });
            
            document.getElementById('edit-form')?.addEventListener('submit', function(e) {
                if (!validateServiceForm('edit-form')) {
                    e.preventDefault();
                }
            });
        });
    
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
        function openEditModal(serviceId) {
            fetch(`/admin/services/${serviceId}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit-name').value = data.name;
                    document.getElementById('edit-price').value = data.price;
                    
                    // Set selected staff
                    const staffSelect = $('#edit-staff');
                    staffSelect.val(data.staff).trigger('change');
                    
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
                    document.getElementById('edit-form').action = `/admin/services/${serviceId}`;
                    
                    openModal('edit-modal');
                });
        }
    
        // Confirm delete
        function confirmDelete(serviceId) {
            if (confirm('Вы уверены, что хотите удалить эту услугу?')) {
                document.getElementById(`delete-form-${serviceId}`).submit();
            }
        }
    </script>
</body>
</html>