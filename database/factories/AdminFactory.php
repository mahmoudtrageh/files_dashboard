<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => \Illuminate\Support\Str::random(10),
            'is_super' => false,
            'status' => true,
        ];
    }
    
    public function superAdmin(): static
    {
        return $this->afterCreating(function (Admin $admin) {
            $admin->is_super = true;
            $admin->save();
            $admin->assignRole('super-admin');
        });
    }
    public function admin(): static
    {
        return $this->afterCreating(function (Admin $admin) {
            $admin->is_super = false;
            $admin->save();
            $admin->assignRole('admin');
        });
    }
}
