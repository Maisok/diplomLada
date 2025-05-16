<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => $this->faker->optional()->imageUrl(),
        ];
    }

    /**
     * Указать, что у услуги нет изображения
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
     * Указать конкретную цену
     *
     * @param float $price
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withPrice(float $price)
    {
        return $this->state(function (array $attributes) use ($price) {
            return [
                'price' => $price,
            ];
        });
    }

    /**
     * Указать конкретное название
     *
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withName(string $name)
    {
        return $this->state(function (array $attributes) use ($name) {
            return [
                'name' => $name,
            ];
        });
    }

    /**
     * Указать сотрудников для услуги
     *
     * @param array $staffIds
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withStaff(array $staffIds = [])
    {
        return $this->afterCreating(function (Service $service) use ($staffIds) {
            if (!empty($staffIds)) {
                $service->staff()->attach($staffIds);
            } else {
                $service->staff()->attach(
                    \App\Models\Staff::factory()->count(2)->create()
                );
            }
        });
    }
}