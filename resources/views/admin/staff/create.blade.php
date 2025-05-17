<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Добавить сотрудника</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #faf9f7;
        }
        .hero-section {
            background: linear-gradient(90deg, rgba(139,95,77,0.1) 0%, rgba(139,95,77,0.05) 100%);
        }
        .form-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .form-title {
            font-family: 'Playfair Display', serif;
            color: #333;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.75rem;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-bottom-color: #8b5f4d;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #8b5f4d;
            font-weight: 500;
        }
        .form-select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 25px;
            margin-bottom: 1.5rem;
            font-size: 1rem;
            background-color: white;
        }
        .submit-btn {
            background-color: #8b5f4d;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
            transition: background-color 0.3s;
            margin-top: 1rem;
        }
        .submit-btn:hover {
            background-color: #6d4a3a;
        }
        .error-text {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: -1rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="hero-section w-full relative flex items-center justify-center h-[200px] md:h-[300px]">
        <img src="{{asset('img/6.png')}}" alt="Фон" class="absolute top-0 left-0 w-full h-full object-cover opacity-30">
        <div class="relative z-20 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-[#8b5f4d] mb-4">Добавить сотрудника</h1>
        </div>
    </section>

    <!-- Main Content -->
    <main>
        <div class="form-container">
            <h2 class="form-title">Данные сотрудника</h2>
            
            <form action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf
                
                <div>
                    <label class="form-label" for="first_name">Имя</label>
                    <input type="text" id="first_name" name="first_name" placeholder="Введите имя" maxlength="50" class="form-input">
                    @error('first_name')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="form-label" for="last_name">Фамилия</label>
                    <input type="text" id="last_name" name="last_name" placeholder="Введите фамилию" maxlength="50" class="form-input">
                    @error('last_name')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="form-label" for="position">Должность</label>
                    <input type="text" id="position" name="position" placeholder="Введите должность" maxlength="30" class="form-input">
                    @error('position')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                
                <div class="form-group">
                    <label for="is_active">Статус</label>
                    <select name="is_active" id="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $service->is_active ?? true) ? 'selected' : '' }}>Активный</option>
                        <option value="0" {{ old('is_active', $service->is_active ?? false) ? 'selected' : '' }}>Неактивный</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="specialist_id">Специализация</label>
                    <select id="specialist_id" name="specialist_id" class="form-select">
                        @foreach($specialists as $specialist)
                            <option value="{{ $specialist->id }}">{{ $specialist->name }}</option>
                        @endforeach
                    </select>
                    @error('specialist_id')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="form-label" for="image">Фотография</label>
                    <input type="file" id="image" name="image" class="form-input" style="padding: 0.5rem 0; border: none;">
                    @error('image')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                
                <button type="submit" class="submit-btn">Добавить сотрудника</button>
            </form>
        </div>
    </main>

    <x-footer/>

    <script>
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        function validateForm() {
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            const positionInput = document.getElementById('position');

            if (!/^[a-zA-Zа-яА-Я\s]+$/.test(firstNameInput.value)) {
                alert('Имя должно содержать только буквы и пробелы.');
                return false;
            }

            if (!/^[a-zA-Zа-яА-Я\s]+$/.test(lastNameInput.value)) {
                alert('Фамилия должна содержать только буквы и пробелы.');
                return false;
            }

            if (!/^[a-zA-Zа-яА-Я\s]+$/.test(positionInput.value)) {
                alert('Должность должна содержать только буквы и пробелы.');
                return false;
            }

            if (positionInput.value.length > 30) {
                alert('Должность не должна превышать 30 символов.');
                return false;
            }

            return true;
        }

        document.getElementById('first_name').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
            this.value = capitalizeFirstLetter(this.value);
        });

        document.getElementById('last_name').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
            this.value = capitalizeFirstLetter(this.value);
        });

        document.getElementById('position').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
            this.value = capitalizeFirstLetter(this.value);
            if (this.value.length > 30) {
                this.value = this.value.slice(0, 30);
            }
        });
    </script>
</body>
</html>