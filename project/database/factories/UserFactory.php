<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // пароль по умолчанию — password
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Указываем роль пользователя как 'admin'
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function admin()
    {
        return $this->state([
            'role' => 'admin',
        ]);
    }

    /**
     * Указываем роль пользователя как 'manager'
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function manager()
    {
        return $this->state([
            'role' => 'manager',
        ]);
    }
}