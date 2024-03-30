<?php

declare(strict_types=1);

namespace App\Transformers;


use App\DTOs\TaskDTO;
use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskTransformer
{
    public static function toDTO(Task $task): TaskDTO
    {
        return new TaskDTO(
            $task->id,
            $task->title,
            $task->description,
            $task->date->format('Y-m-d'),
            $task->location,
            $task->details,
            $task->user_id
        );
    }

    public static function toModel(TaskDTO $taskDTO): Task
    {
        $task = new Task();

        $task->exists = $taskDTO->getId() !== null;

        if ($taskDTO->getId()) {
            $task->id = $taskDTO->getId();
        }

        $task->title = $taskDTO->getTitle();
        $task->description = $taskDTO->getDescription();
        $task->date = $taskDTO->getDate();
        $task->location = $taskDTO->getLocation();
        $task->details = $taskDTO->getDetails();
        $task->user_id = $taskDTO->getUserId();

        return $task;
    }

    public static function fromPaginatedResults(LengthAwarePaginator $paginator): array
    {
        $data = $paginator->getCollection()->map(function (Task $task) {
            return self::modelToArray($task);
        })->toArray();

        return [
            'data' => $data,
            'pagination' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public static function toArray(TaskDTO $taskDTO): array
    {
        return [
            'id' => $taskDTO->getId(),
            'title' => $taskDTO->getTitle(),
            'description' => $taskDTO->getDescription(),
            'date' => $taskDTO->getDate(),
            'location' => $taskDTO->getLocation(),
            'details' => $taskDTO->getDetails(),
            'user_id' => $taskDTO->getUserId(),
        ];
    }

    public static function modelToArray(Task $task): array
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'date' => $task->date->format('Y-m-d'),
            'location' => $task->location,
            'details' => $task->details,
            'user_id' => $task->user_id,
        ];
    }
}

