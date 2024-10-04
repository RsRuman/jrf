<?php

namespace App\Http\Controllers\Api\V1;

use AllowDynamicProperties;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Interfaces\TaskInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

#[AllowDynamicProperties]
class TaskController extends Controller
{
    public function __construct(TaskInterface $task)
    {
        $this->task = $task;
    }

    /**
     * List off task
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $tasks = $this->task->getTasks($request);
        $tasks = TaskResource::collection($tasks);
        $tasks = $this->collectionResponse($tasks);

        return Response::json([
            'message' => 'Tasks retrieved successfully.',
            'data' => $tasks,
        ], HttpResponse::HTTP_OK);
    }

    /**
     * Show task
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $task = $this->task->getTask($id);

        if (!$task) {
            return Response::json([
                'message' => 'Task not found.',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        $task = new TaskResource($task);

        return Response::json([
            'message' => 'Task retrieved successfully.',
            'data' => $task,
        ], HttpResponse::HTTP_OK);

    }

    /**
     * Create task
     * @param CreateTaskRequest $request
     * @return JsonResponse
     */
    public function create(CreateTaskRequest $request): JsonResponse
    {
        $data               = $request->safe()->only(['title', 'description', 'due_date']);
        $data['created_by'] = auth('api')->id();

        try {
            DB::beginTransaction();

            $task = $this->task->createTask($data);

            if (!$task) {
                throw new Exception( 'Could not create task.');
            }

            # Attach assignees
            $task->assignees()->attach($request->input('assignees'));

            DB::commit();

            return Response::json([
                'message' => 'Task created successfully.',
            ], HttpResponse::HTTP_CREATED);

        } catch (Exception $exception) {
            DB::rollBack();

            Log::error('Task creation failed: ' . $exception->getMessage());

            return Response::json([
                'message' => 'Task could not be created.',
            ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Update task
     * @param UpdateTaskRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        $task = $this->task->getTask($id);

        if (!$task) {
            return Response::json([
                'message' => 'Task not found.',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        if (!Gate::allows('update', $task)) {
            return response()->json([
                'message' => 'Permission denied. You are not authorized to update this task.',
            ], HttpResponse::HTTP_FORBIDDEN);
        }

        $data = $request->safe()->only(['title', 'description', 'due_date', 'status']);

        try {
            DB::beginTransaction();

            if (!$this->task->updateTask($task, $data)) {
                throw new Exception( 'Could not update task.');
            }

            # Sync assignees
            $task->assignees()->sync($request->input('assignees'));

            DB::commit();

            return Response::json([
                'message' => 'Task updated successfully.',
            ], HttpResponse::HTTP_OK);

        } catch (Exception $exception) {
            DB::rollBack();

            Log::error('Task update failed: ' . $exception->getMessage());

            return Response::json([
                'message' => 'Task could not be updated.',
            ], httpResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Delete task
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $task = $this->task->getTask($id);

        if (!$task) {
            return Response::json([
                'message' => 'Task not found.',
            ], HttpResponse::HTTP_NOT_FOUND);
        }

        if (!Gate::allows('delete', $task)) {
            return response()->json([
                'message' => 'Permission denied. You are not authorized to delete this task.',
            ], HttpResponse::HTTP_FORBIDDEN);
        }

        $this->task->deleteTask($task);

        return Response::json([
            'message' => 'Task deleted successfully.',
        ], HttpResponse::HTTP_OK);
    }
}
