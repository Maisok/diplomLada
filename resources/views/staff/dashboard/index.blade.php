
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Dashboard Content -->
    <main class="p-6" id="dynamic-content">
        <!-- Welcome Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden mb-6">
            <div class="p-6 bg-gradient-to-r from-amber-50 to-amber-100">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h3 class="text-xl font-bold mb-2">Добро пожаловать, {{ auth('staff')->user()->first_name }}!</h3>
                        <p class="text-gray-700">
                            @if($todayAppointmentsCount > 0)
                                Сегодня у вас запланировано {{ $todayAppointmentsCount }} {{ trans_choice('запись|записи|записей', $todayAppointmentsCount) }}.
                                @if($lastAppointmentToday)
                                    Последняя запись в {{ $lastAppointmentToday->start_time->format('H:i') }}.
                                @endif
                            @else
                                Сегодня у вас нет запланированных записей.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="card bg-white rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-gray-500">Сегодня</h4>
                        <p class="text-2xl font-bold">{{ $todayAppointmentsCount }} {{ trans_choice('запись|записи|записей', $todayAppointmentsCount) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card bg-white rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-gray-500">За месяц</h4>
                        <p class="text-2xl font-bold">{{ $monthAppointmentsCount }} {{ trans_choice('запись|записи|записей', $monthAppointmentsCount) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="card bg-white rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h4 class="text-gray-500">Выручка (всего)</h4>
                        <p class="text-2xl font-bold">{{ number_format($totalRevenue, 0, ',', ' ') }} ₽</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold">Ближайшие записи</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($upcomingAppointments as $appointment)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="font-medium">{{ $appointment->service->name }}</h4>
                            <p class="text-sm text-gray-500">
                                {{ $appointment->user->name }} - {{ $appointment->start_time->format('H:i') }}
                            </p>
                        </div>
                        <div class="ml-auto">
                            @if($appointment->status == 'confirmed')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Подтверждена</span>
                            @elseif($appointment->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Ожидает</span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Отменена</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-gray-500">
                    Нет предстоящих записей
                </div>
                @endforelse
            </div>
           
        </div>
    </main>