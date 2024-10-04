<?php

namespace App\Repositories;

use App\Interfaces\TaskInterface;
use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TaskRepository implements TaskInterface
{
    /**
     * get tasks
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function getTasks(Request $request): LengthAwarePaginator
    {
        $perPage = $request->query('perPage', 10);

        return Task::with(['assignees', 'author'])
        ->filter($request->only('search', 'assignee', 'due_date'))
        ->paginate($perPage);
    }

    /**
     * Get task
     * @param $id
     * @return Model|null
     */
    public function getTask($id): Model|null
    {
        return Task::with(['assignees', 'author'])->find($id);
    }

    /**
     * Create task
     * @param array $data
     * @return mixed
     */
    public function createTask(array $data): mixed
    {
        return Task::create($data);
    }

    /**
     * Update task
     * @param Task $task
     * @param array $data
     * @return bool
     */
    public function updateTask(Task $task, array $data): bool
    {
        return $task->update($data);
    }

    /**
     * Delete task
     * @param Task $task
     * @return bool|null
     */
    public function deleteTask(Task $task): ?bool
    {
        return $task->delete();
    }
}
