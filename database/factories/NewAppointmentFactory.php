<?php

namespace Database\Factories;

use App\Models\NewAppointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewAppointmentFactory extends Factory
{
    protected $model = NewAppointment::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // создаём пользователя
            'service_id' => \App\Models\Service::factory(), // создаём услугу
            'staff_id' => \App\Models\Staff::factory(), // создаём сотрудника
            'branch_id' => \App\Models\Branch::factory(), // создаём филиал
            'start_time' => now()->addHour(),
            'end_time' => now()->addHours(2),
            'status' => 'pending',
        ];
    }

    /**
     * Указать конкретный статус
     */
    public function withStatus(string $status)
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status,
        ]);
    }
}