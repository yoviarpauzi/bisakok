<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class AdminFactory extends Factory
{
    protected $model = User::class;

    public static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => 'admin',
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10)
        ];
    }
}
