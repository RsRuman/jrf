<?php

namespace App\Http\Requests;

use App\Enums\TaskStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('id');

        $acceptableStatus = [
            TaskStatusEnum::TODO->value,
            TaskStatusEnum::IN_PROGRESS->value,
            TaskStatusEnum::DONE->value,
        ];

        return [
            'title'        => "required|string|max:55|unique:tasks,title," . $id,
            'description'  => "required|string|max:255",
            'due_date'     => "required|date",
            'assignees'    => "required|array",
            'assignees.*'  => 'numeric|exists:users,id',
            'status'       => "required|in:" .implode(",", $acceptableStatus),
        ];
    }
}
