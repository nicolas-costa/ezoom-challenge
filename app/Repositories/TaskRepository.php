<?php

declare(strict_types=1);

namespace App\Repositories;

use App\DTOs\TaskDTO;
use App\Exceptions\UnableToCreateTaskException;
use App\Exceptions\UnableToDeleteTaskException;
use App\Exceptions\UnableToFindTaskException;
use App\Exceptions\UnableToUpdateTaskException;
use App\Models\Task;
use App\Transformers\TaskTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskRepository
{
    public function __construct(private Task $task) {}

    public function getPaginatedFromUser(int $userId, int $perPage = 10, int $currentPage = 1): array
    {
        $tasks = $this->task
            ->where('user_id', $userId)
            ->paginate(perPage: $perPage, page: $currentPage);

        return TaskTransformer::fromPaginatedResults($tasks);
    }

    /**
     * @throws UnableToCreateTaskException
     */
    public function create(TaskDTO $taskDTO): int
    {
        $model = TaskTransformer::toModel($taskDTO);

        if (!$model->save()) {
            throw new UnableToCreateTaskException();
        }

        return $model->id;
    }

    /**
     * @throws UnableToUpdateTaskException
     */
    public function update(TaskDTO $taskDTO): void
    {
        $model = TaskTransformer::toModel($taskDTO);

        if (!$model->update()) {
            throw new UnableToUpdateTaskException();
        }
    }

    /**
     * @throws UnableToDeleteTaskException
     * @throws UnableToFindTaskException
     */
    public function delete(TaskDTO $taskDTO): void
    {
        try {
            $task = $this->task
                ->where('id', $taskDTO->getId())
                ->firstOrFail();
        } catch (ModelNotFoundException) {
            throw new UnableToFindTaskException();
        }

        if (!$task->delete()) {
            throw new UnableToDeleteTaskException();
        }
    }
}
