<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BB | Управление сертификатами</title>
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
            background: linear-gradient(90deg, rgba(139,95,77,0.7) 0%, rgba(139,95,77,0.3) 100%);
        }
        .btn-admin {
            background-color: #8b5f4d;
            transition: all 0.3s ease;
        }
        .btn-admin:hover {
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
        .status-active {
            background-color: #f0fdf4;
            color: #166534;
        }
        .status-used {
            background-color: #eff6ff;
            color: #1e40af;
        }
        .status-expired {
            background-color: #f5f5f4;
            color: #57534e;
        }
        .status-cancelled {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body class="antialiased">
    <x-header/>
    
    <!-- Hero Section -->
    <section class="relative h-[300px] overflow-hidden">
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <img src="{{asset('img/6.png')}}" alt="Управление сертификатами" class="w-full h-full object-cover">
        <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center px-4">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Управление подарочными сертификатами</h1>
            <p class="text-white text-lg max-w-2xl">Просмотр и изменение статусов сертификатов</p>
        </div>
    </section>

    <!-- Certificates Section -->
    <section class="py-12 px-4 max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-md overflow-hidden p-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl font-bold text-[#8b5f4d]">Список сертификатов</h2>
                <form method="GET" action="{{ route('admin.certificates.index') }}" class="flex space-x-4">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Поиск по коду..." 
                        value="{{ request('search') }}"
                        class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#8b5f4d]">
                        
                    <select 
                        name="status" 
                        onchange="this.form.submit()"
                        class="border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#8b5f4d]">
                        <option value="">Все статусы</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активные</option>
                        <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>Использованные</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Просроченные</option>
                    </select>

                    @if(request()->has('search') || request()->has('status'))
                    <div class="mb-4">
                        <a href="{{ route('admin.certificates.index') }}" class="text-[#8b5f4d] hover:underline">
                            Сбросить фильтры
                        </a>
                    </div>
                @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Код</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Номинал</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Получатель</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата создания</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действителен до</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($certificates as $certificate)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $certificate->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($certificate->amount, 0, ',', ' ') }} ₽</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $certificate->recipient_name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $certificate->created_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $certificate->expires_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if(!$certificate->is_used && $certificate->expires_at > now()) bg-green-100 text-green-800
                                    @elseif($certificate->is_used) bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if(!$certificate->is_used && $certificate->expires_at > now()) Активен
                                    @elseif($certificate->is_used) Использован
                                    @else Просрочен @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('admin.certificates.update', $certificate) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="is_used" onchange="this.form.submit()" 
                                        class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-[#8b5f4d]">
                                        <option value="0" {{ !$certificate->is_used ? 'selected' : '' }}>Активен</option>
                                        <option value="1" {{ $certificate->is_used ? 'selected' : '' }}>Использован</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $certificates->appends(request()->query())->links() }}
            </div>
        </div>
    </section>


</body>
</html>