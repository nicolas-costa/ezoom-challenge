<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\v1;

use App\DTOs\TaskDTO;
use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractTaskRequest extends FormRequest
{
    public function toDTO(): TaskDTO
    {
        $parameters = $this->input();

        /** @var TaskDTO $task */
        $task = $this->route('task');

        return new TaskDTO(
            $task?->getId() ?? null,
            $parameters['title'],
            $parameters['description'] ?? null,
            $parameters['date'],
            $parameters['location'],
            $parameters['details'] ?? null,
            $task->getUserId() ?? null
        );
    }
}
