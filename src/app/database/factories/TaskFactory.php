<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [

            'project_id' => Project::factory(),

            'title' => fake()->sentence(4),

            'description' => fake()->paragraph(),

            'priority' => fake()->randomElement([
                TaskPriority::LOW->value,
                TaskPriority::MEDIUM->value,
                TaskPriority::HIGH->value,
            ]),

            'status' => fake()->randomElement([
                TaskStatus::TODO->value,
                TaskStatus::IN_PROGRESS->value,
                TaskStatus::DONE->value,
            ]),

            'due_date' => fake()->dateTimeBetween(
                '-10 days',
                '+30 days'
            ),
        ];
    }
}