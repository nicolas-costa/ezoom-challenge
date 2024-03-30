<?php

declare(strict_types=1);

namespace App\Transformers\Response;


use App\DTOs\TaskDTO;
use App\Transformers\TaskTransformer;

class TaskResponseTransformer
{
    public static function created(int $id): array
    {
        return [
            'id' => $id,
            'url' => env('APP_URL') . '/api/v1/tasks/' . $id
        ];
    }

    public static function show(TaskDTO $taskDTO): array
    {
        return TaskTransformer::toArray($taskDTO);
    }
}
