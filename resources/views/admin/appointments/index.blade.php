<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Управление записями</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="antialiased">
    <x-header/>
    
    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-8">
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold">Управление записями</h1>
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary px-4 py-2 rounded-md">
                    Назад в панель
                </a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="py-3 px-6 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Клиент
                            </th>
                            <th class="py-3 px-6 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Услуга
                            </th>
                            <th class="py-3 px-6 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Дата/Время
                            </th>
                            <th class="py-3 px-6 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Статус
                            </th>
                            <th class="py-3 px-6 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                Действия
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $appointment)
                        <tr class="hover:bg-gray-50">
                            <td class="py-4 px-6 border-b border-gray-200">
                                {{ $appointment->id }}
                            </td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                {{ $appointment->user->name }}
                            </td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                {{ $appointment->service->name }}
                            </td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                {{ $appointment->start_time->format('d.m.Y H:i') }}
                            </td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($appointment->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($appointment->status == 'active') bg-green-100 text-green-800
                                    @elseif($appointment->status == 'completed') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 border-b border-gray-200">
                                <form action="{{ route('admin.appointments.update-status', $appointment) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="border rounded px-3 py-1 text-sm">
                                        <option value="pending" {{ $appointment->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                                        <option value="active" {{ $appointment->status == 'active' ? 'selected' : '' }}>Активна</option>
                                        <option value="completed" {{ $appointment->status == 'completed' ? 'selected' : '' }}>Завершена</option>
                                        <option value="cancelled" {{ $appointment->status == 'cancelled' ? 'selected' : '' }}>Отменена</option>
                                    </select>
                                    <button type="submit" class="bg-[#8b5f4d] text-white px-3 py-1 rounded text-sm hover:bg-[#6d4a3a]">
                                        Обновить
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </div>
    </section>

</body>
</html>