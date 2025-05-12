<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-semibold">Список записей</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Клиент</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Услуга</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата и время</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($appointments as $appointment)
                <tr class="hover:bg-gray-50" id="appointment-{{ $appointment->id }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="text-sm font-medium text-gray-900">{{ $appointment->user->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $appointment->service->name }}</div>
                        <div class="text-sm text-gray-500">{{ number_format($appointment->service->price, 0, ',', ' ') }} ₽</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $appointment->start_time->format('d.m.Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $appointment->start_time->format('H:i') }} - {{ $appointment->end_time->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="relative" id="status-container-{{ $appointment->id }}">
                            @if($appointment->status == 'pending')
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Ожидает</span>
                            @elseif($appointment->status == 'active')
                                <select 
                                    class="status-select appearance-none bg-white border border-gray-300 rounded-md pl-3 pr-8 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                    data-appointment-id="{{ $appointment->id }}"
                                    onchange="updateStatus(this)"
                                >
                                    <option value="active" selected>Подтверждена</option>
                                    <option value="completed">Выполнена</option>
                                    <option value="cancelled">Отменена</option>
                                </select>
                            @elseif($appointment->status == 'completed')
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Выполнена</span>
                            @elseif($appointment->status == 'cancelled')
                                <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Отменена</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Нет записей
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-4 border-t border-gray-100">
        {{ $appointments->links() }}
    </div>
</div>

<script>

function updateStatusUI(appointmentId, newStatus) {
    const container = document.getElementById(`status-container-${appointmentId}`);
    if (!container) return;
    
    // Создаем новый элемент статуса
    let statusHtml;
    switch(newStatus) {
        case 'completed':
            statusHtml = `<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Выполнена</span>`;
            break;
        case 'cancelled':
            statusHtml = `<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Отменена</span>`;
            break;
        default:
            return;
    }
    
    // Заменяем содержимое контейнера
    container.innerHTML = statusHtml;
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-select').forEach(select => {
        select.dataset.originalValue = select.value;
    });
});
</script>
