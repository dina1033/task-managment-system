<?php

namespace Database\Factories;

use App\Enums\ProjectStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'name' => fake()->sentence(3),

            'description' => fake()->paragraph(),

            'status' => fake()->randomElement([
                ProjectStatus::ACTIVE->value,
                ProjectStatus::COMPLETED->value,
                ProjectStatus::ARCHIVED->value,
            ]),
        ];
    }
}