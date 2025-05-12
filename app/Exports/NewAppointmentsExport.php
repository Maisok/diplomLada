<?php

namespace App\Exports;

use App\Models\NewAppointment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NewAppointmentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        return NewAppointment::with(['user', 'service', 'staff', 'branch'])
            ->where('created_at', '>=', $this->date)
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Клиент',
            'Email клиента',
            'Услуга',
            'Цена услуги',
            'Сотрудник',
            'Филиал',
            'Дата и время начала',
            'Дата и время окончания',
            'Статус',
            'Дата создания',
        ];
    }

    public function map($appointment): array
    {
        return [
            $appointment->id,
            $appointment->user->name ?? 'Не указан',
            $appointment->user->email ?? 'Не указан',
            $appointment->service->name ?? 'Услуга удалена',
            $appointment->service->price ?? '0',
            $appointment->staff ? $appointment->staff->first_name . ' ' . $appointment->staff->last_name : 'Сотрудник удален',
            $appointment->branch->name ?? 'Филиал удален',
            $appointment->start_time,
            $appointment->end_time,
            $this->getStatusText($appointment->status),
            $appointment->created_at,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    protected function getStatusText($status)
    {
        $statuses = [
            'pending' => 'Ожидание',
            'active' => 'Активна',
            'completed' => 'Завершена',
            'cancelled' => 'Отменена',
        ];

        return $statuses[$status] ?? $status;
    }
}