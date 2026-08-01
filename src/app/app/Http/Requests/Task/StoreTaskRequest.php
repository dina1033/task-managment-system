<?php

namespace App\Http\Requests\Task;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rules\Enum;

class StoreTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['required', new Enum(TaskPriority::class)],
            'status' => ['sometimes', new Enum(TaskStatus::class)],
            'due_date' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    /**
     * Get custom body parameters for Scribe.
     */
    public function bodyParameters(): array
    {
        return [
            'title' => [
                'description' => 'Task title.',
                'example' => 'Implement authentication',
            ],

            'description' => [
                'description' => 'Task description.',
                'example' => 'Implement Laravel Sanctum authentication.',
            ],

            'priority' => [
                'description' => 'Task priority.',
                'example' => 'high',
            ],

            'status' => [
                'description' => 'Task status.',
                'example' => 'todo',
            ],

            'due_date' => [
                'description' => 'Task due date.',
                'example' => '2026-08-15',
            ],
        ];
    }
}
