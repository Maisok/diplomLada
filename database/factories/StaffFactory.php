<?php

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'position' => $this->faker->randomElement(['master', 'manager', 'admin', 'receptionist']),
            'image' => $this->faker->optional()->imageUrl(),
            'branch_id' => Branch::factory(),
            'login' => $this->faker->unique()->userName,
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Указать, что сотрудник является администратором
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'position' => 'admin',
            ];
        });
    }

    /**
     * Указать, что у сотрудника нет изображения
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
     * Указать конкретный филиал
     *
     * @param  int  $branchId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forBranch($branchId)
    {
        return $this->state(function (array $attributes) use ($branchId) {
            return [
                'branch_id' => $branchId,
            ];
        });
    }

    /**
     * Указать конкретную должность
     *
     * @param  string  $position
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withPosition($position)
    {
        return $this->state(function (array $attributes) use ($position) {
            return [
                'position' => $position,
            ];
        });
    }
}