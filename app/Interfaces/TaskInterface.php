<?php

namespace App\Interfaces;

use App\Models\Task;
use Illuminate\Http\Request;

interface TaskInterface
{
    public function getTasks(Request $request);
    public function getTask($id);
    public function createTask(array $data);
    public function updateTask(Task $task, array $data);
    public function deleteTask(Task $task);
}
