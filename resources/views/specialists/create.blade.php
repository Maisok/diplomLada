<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
</head>
<body>
    <x-header/>

    <!-- Create Specialist Form -->
    <section class="flex items-center justify-center w-full mt-8 px-4">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-12 w-full max-w-md border border-gray-300">
            <h2 class="text-2xl font-semibold mb-6">Добавить специалиста</h2>
            <form action="{{ route('admin.specialists.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4" onsubmit="return validateForm()">
                @csrf
                <!-- Name Input -->
                <div>
                    <label class="sr-only" for="name">Имя</label>
                    <input type="text" id="name" name="name" maxlength="50" placeholder="Имя" class="w-full px-4 py-2 rounded-full border border-gray-300 text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Service Input -->
                <div>
                    <label class="sr-only" for="service_id">Услуга</label>
                    <select id="service_id" name="service_id" class="w-full px-4 py-2 rounded-full border border-gray-300 text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Image Input -->
                <div>
                    <label class="sr-only" for="image">Изображение</label>
                    <input type="file" id="image" name="image" class="w-full px-4 py-2 rounded-full border border-gray-300 text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                    @error('image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="is_active">Статус</label>
                    <select name="is_active" id="is_active" class="form-control">
                        <option value="1" {{ old('is_active', $service->is_active ?? true) ? 'selected' : '' }}>Активный</option>
                        <option value="0" {{ old('is_active', $service->is_active ?? false) ? 'selected' : '' }}>Неактивный</option>
                    </select>
                </div>
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-100 text-blue-800 font-semibold py-2 rounded-full shadow-md hover:bg-blue-200 mt-4">
                    Добавить
                </button>
            </form>
        </div>
    </section>
    <x-footer/>

    <script>
        function validateForm() {
            const nameInput = document.getElementById('name');

            // Валидация имени
            if (!/^[a-zA-Zа-яА-Я\s]+$/.test(nameInput.value)) {
                alert('Имя должно содержать только буквы и пробелы.');
                return false;
            }

            if (nameInput.value.length > 30) {
                alert('Имя не должно превышать 30 символов.');
                return false;
            }

            return true;
        }

        document.getElementById('name').addEventListener('input', function() {
            // Ограничиваем ввод только символами и пробелами
            this.value = this.value.replace(/[^a-zA-Zа-яА-Я\s]/g, '');

            // Ограничиваем длину до 30 символов
            if (this.value.length > 30) {
                this.value = this.value.slice(0, 30);
            }
        });
    </script>
</body>
</html>