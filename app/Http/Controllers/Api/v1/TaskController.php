<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\DTOs\TaskDTO;
use App\Exceptions\Http\UnableToCreateTaskException as UnableToCreateTaskHttpException;
use App\Exceptions\Http\UnableToUpdateTaskException as UnableToUpdateTaskHttpException;
use App\Exceptions\Http\UnableToDeleteTaskException as UnableToDeleteTaskHttpException;
use App\Exceptions\Http\UnableToFindTaskException as UnableToFindTaskHttpException;
use App\Exceptions\UnableToCreateTaskException;
use App\Exceptions\UnableToDeleteTaskException;
use App\Exceptions\UnableToFindTaskException;
use App\Exceptions\UnableToUpdateTaskException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\CreateTaskRequest;
use App\Http\Requests\Api\v1\UpdateTaskRequest;
use App\Repositories\TaskRepository;
use App\Transformers\Response\TaskResponseTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    public function __construct(
        private TaskRepository $taskRepository
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 10);
        $currentPage = $request->input('current_page', 1);
        $userID = $request->user()->id;


        $tasks = $this->taskRepository
            ->getPaginatedFromUser(
                $userID,
                $perPage,
                $currentPage
            );

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $task = $request->toDTO();
        $task->setUserId($request->user()->id);

        try {
            $id = $this->taskRepository
                ->create($task);

            return response()->json(TaskResponseTransformer::created($id), Response::HTTP_CREATED);
        } catch (UnableToCreateTaskException $exception) {
            throw new UnableToCreateTaskHttpException($exception);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskDTO $taskDTO): JsonResponse
    {
        return response()->json(TaskResponseTransformer::show($taskDTO));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request): JsonResponse
    {
        $task = $request->toDTO();

        try {
            $this->taskRepository
                ->update($task);

            return response()->json([], Response::HTTP_OK);
        } catch (UnableToUpdateTaskException $exception) {
            throw new UnableToUpdateTaskHttpException($exception);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskDTO $taskDTO): JsonResponse
    {
        try {
            $this->taskRepository
                ->delete($taskDTO);

            return response()->json([], Response::HTTP_NO_CONTENT);
        } catch (UnableToDeleteTaskException $exception) {
            throw new UnableToDeleteTaskHttpException($exception);
        } catch (UnableToFindTaskException $exception) {
            throw new UnableToFindTaskHttpException(previous: $exception);
        }
    }
}
