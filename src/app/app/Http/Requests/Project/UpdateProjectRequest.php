<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
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
                'description' => 'Updated project name.',
                'example' => 'Updated Task Management API',
            ],

            'description' => [
                'description' => 'Updated project description.',
                'example' => 'Updated project description.',
            ],

            'status' => [
                'description' => 'Project status.',
                'example' => 'completed',
            ],
        ];
    }
}