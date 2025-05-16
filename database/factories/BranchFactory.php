<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BranchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Branch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $workStart = $this->faker->time('H:i', '08:00');
        $workEnd = $this->faker->time('H:i', $workStart);

        // Убедимся, что время окончания больше времени начала
        if ($workEnd <= $workStart) {
            $workEnd = $this->faker->time('H:i', '20:00');
        }

        return [
            'name' => $this->faker->company . ' Branch',
            'address' => $this->faker->address,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'image' => $this->faker->optional()->imageUrl(),
            'work_time_start' => $workStart,
            'work_time_end' => $workEnd,
        ];
    }

    /**
     * Указать, что у филиала нет изображения
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withoutImage()
    {
        return $this->state(function (array $attributes) {
            return [
                'image' => null,
            ];
        });
    }

    /**
     * Указать конкретное время работы
     *
     * @param string $startTime
     * @param string $endTime
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withWorkingHours($startTime, $endTime)
    {
        return $this->state(function (array $attributes) use ($startTime, $endTime) {
            return [
                'work_time_start' => $startTime,
                'work_time_end' => $endTime,
            ];
        });
    }

    /**
     * Указать конкретное название филиала
     *
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withName($name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name' => $name,
            ];
        });
    }
}