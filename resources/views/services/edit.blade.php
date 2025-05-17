<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Редактировать услугу</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #faf9f7;
        }
        .form-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1px solid #ddd;
            margin-bottom: 1.5rem;
            font-size: 1rem;
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
    
    <main>
        <div class="form-container">
            <h1 style="font-family: 'Playfair Display', serif; color: #333; text-align: center; margin-bottom: 2rem;">Редактировать услугу</h1>
            
            <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="form-label" for="name">Название</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}" maxlength="30" class="form-input">
                    @error('name')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                
                <div>
                    <label class="form-label" for="price">Цена</label>
                    <input type="text" id="price" name="price" value="{{ old('price', $service->price) }}" class="form-input">
                    @error('price')<p class="error-text">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="is_active">Статус</label>
                    <select name="is_active" id="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $service->is_active ?? true) ? 'selected' : '' }}>Активный</option>
                        <option value="0" {{ old('is_active', $service->is_active ?? false) ? 'selected' : '' }}>Неактивный</option>
                    </select>
                </div>
                
                <div>
                    <label class="form-label" for="image">Изображение</label>
                    <input type="file" id="image" name="image" class="form-input" style="padding: 0.5rem 0;">
                    @error('image')<p class="error-text">{{ $message }}</p>@enderror
                </div>
                
                <button type="submit" class="submit-btn">Сохранить изменения</button>
            </form>
        </div>
    </main>

    <x-footer/>

    <script>
        function validateForm() {
            const nameInput = document.getElementById('name');
            const priceInput = document.getElementById('price');

            if (!/^[a-zA-Zа-яА-Я\s]+$/.test(nameInput.value)) {
                alert('Название должно содержать только буквы и пробелы.');
                return false;
            }

            if (!/^\d{1,5}$/.test(priceInput.value)) {
                alert('Цена должна содержать только цифры (до 5 знаков).');
                return false;
            }

            return true;
        }

        document.getElementById('name').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');
        });

        document.getElementById('price').addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 5);
        });
    </script>
</body>
</html>