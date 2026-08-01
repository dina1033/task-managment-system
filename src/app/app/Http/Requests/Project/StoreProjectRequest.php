<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', new Enum(ProjectStatus::class)],
        ];
    }
    /**
     * Get custom body parameters for Scribe.
     */
    public function bodyParameters(): array
    {
        return [
            'name' => [
                'description' => 'Project name.',
                'example' => 'Task Management API',
            ],

            'description' => [
                'description' => 'Project description.',
                'example' => 'Laravel REST API with authentication and task management.',
            ],

            'status' => [
                'description' => 'Project status.',
                'example' => 'active',
            ],
        ];
    }
}