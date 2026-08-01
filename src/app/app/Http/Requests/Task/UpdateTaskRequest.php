<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['sometimes', new Enum(TaskPriority::class)],
            'status' => ['sometimes', new Enum(TaskStatus::class)],
            'due_date' => ['sometimes','nullable', 'date'],
        ];
    }

    /**
     * Get custom body parameters for Scribe.
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Updated task title.',
                'example' => 'Finish API Documentation',
            ],

            'description' => [
                'description' => 'Updated task description.',
                'example' => 'Complete API documentation using Scribe.',
            ],

            'priority' => [
                'description' => 'Task priority.',
                'example' => 'medium',
            ],

            'status' => [
                'description' => 'Task status.',
                'example' => 'in_progress',
            ],

            'due_date' => [
                'description' => 'Updated due date.',
                'example' => '2026-08-20',
            ],
        ];
    }
}
