<?php

namespace Tests\Unit\Transformers;

use App\DTOs\TaskDTO;
use App\Models\Task;
use App\Transformers\TaskTransformer;
use DateTime;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class TaskTransformerTest extends TestCase
{
    public function testToDTO()
    {
        $task = new Task();
        $task->id = 1;
        $task->title = 'Task';
        $task->description = 'Task description';
        $task->date = new DateTime('2024-12-25');
        $task->location = 'Office';
        $task->details = 'Task details';
        $task->user_id = 1;

        $taskDTO = TaskTransformer::toDTO($task);

        $this->assertEquals(1, $taskDTO->getId());
        $this->assertEquals('Task', $taskDTO->getTitle());
        $this->assertEquals('Task description', $taskDTO->getDescription());
        $this->assertEquals('2024-12-25', $taskDTO->getDate());
        $this->assertEquals('Office', $taskDTO->getLocation());
        $this->assertEquals('Task details', $taskDTO->getDetails());
        $this->assertEquals(1, $taskDTO->getUserId());
    }

    public function testToModel()
    {
        $taskDTO = new TaskDTO(
            1,
            'Task',
            'Task description',
            '2024-12-25',
            'Office',
            'Task details',
            1
        );

        $task = TaskTransformer::toModel($taskDTO);

        $this->assertEquals(1, $task->id);
        $this->assertEquals('Task', $task->title);
        $this->assertEquals('Task description', $task->description);
        $this->assertEquals('2024-12-25', $task->date->format('Y-m-d'));
        $this->assertEquals('Office', $task->location);
        $this->assertEquals('Task details', $task->details);
        $this->assertEquals(1, $task->user_id);
    }

    public function testFromPaginatedResults()
    {
        $tasks = [
            new Task([
                'title' => 'Task 1',
                'description' => 'Description 1',
                'date' => new DateTime('2024-12-25'),
                'location' => 'Office 1',
                'details' => 'Details 1',
                'user_id' => 1
            ]),
            new Task([
                'title' => 'Task 2',
                'description' => 'Description 2',
                'date' => new DateTime('2024-12-26'),
                'location' => 'Office 2',
                'details' => 'Details 2',
                'user_id' => 2
            ])
        ];

        $paginator = $this->createMock(LengthAwarePaginator::class);
        $paginator->method('getCollection')->willReturn(collect($tasks));
        $paginator->method('total')->willReturn(2);
        $paginator->method('perPage')->willReturn(10);
        $paginator->method('currentPage')->willReturn(1);
        $paginator->method('lastPage')->willReturn(1);
        $paginator->method('firstItem')->willReturn(1);
        $paginator->method('lastItem')->willReturn(2);

        $result = TaskTransformer::fromPaginatedResults($paginator);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('pagination', $result);
        $this->assertCount(2, $result['data']);
        $this->assertEquals(2, $result['pagination']['total']);
        $this->assertEquals(10, $result['pagination']['per_page']);
        $this->assertEquals(1, $result['pagination']['current_page']);
        $this->assertEquals(1, $result['pagination']['last_page']);
        $this->assertEquals(1, $result['pagination']['from']);
        $this->assertEquals(2, $result['pagination']['to']);
    }

    public function testToArray()
    {
        $taskDTO = new TaskDTO(
            null,
            'Task',
            'Task description',
            '2024-12-25',
            'Office',
            'Task details',
            null
        );

        $result = TaskTransformer::toArray($taskDTO);

        $this->assertIsArray($result);
        $this->assertCount(7, $result);
        $this->assertEquals('Task', $result['title']);
        $this->assertEquals('Task description', $result['description']);
        $this->assertEquals('2024-12-25', $result['date']);
        $this->assertEquals('Office', $result['location']);
        $this->assertEquals('Task details', $result['details']);
        $this->assertNull($result['user_id']);
    }
}
