<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);


        $projects = \App\Models\Project::factory()
            ->count(5)
            ->create([
                'user_id' => $user->id,
            ]);


        foreach ($projects as $project) {

            \App\Models\Task::factory()
                ->count(10)
                ->create([
                    'project_id' => $project->id,
                ]);
        }
    }
}