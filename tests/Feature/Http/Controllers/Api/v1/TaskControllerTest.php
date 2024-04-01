<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers\Api\v1;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use DatabaseTransactions;
    use DatabaseMigrations;

    /**
     * Test the index method of the TaskController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = User::factory()->create();
        $tasks = Task::factory()->count(5)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/v1/tasks');

        $response->assertStatus(200);

        foreach ($tasks as $task) {
            $response->assertJsonFragment([
                'title' => $task->title,
            ]);
        }
    }

    /**
     * Test the store method of the TaskController.
     *
     * @return void
     */
    public function testStore()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/v1/tasks', [
            'title' => 'TAsk',
            'date' => '2025-01-01',
            'location' => 'place',
            'details' => 'details'
        ]);

        $response->assertStatus(201);

        $response->assertJson([
            'url' => env('APP_URL') . '/api/v1/tasks/' . $response->json('id'),
        ]);
    }

    /**
     * Test the show method of the TaskController.
     *
     * @return void
     */
    public function testShow()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/v1/tasks/' . $task->id);

        $response->assertStatus(200);

        $response->assertJson([
            'title' => $task->title,
        ]);
    }

    /**
     * Test the update method of the TaskController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put('/api/v1/tasks/' . $task->id, [
            'title' => 'Updated Title',
            'description' => 'description',
            'location' => $task->location,
            'date' => $task->date->format('Y-m-d')
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'description' => 'description'
        ]);
    }

    /**
     * Test the destroy method of the TaskController.
     *
     * @return void
     */
    public function testDestroy()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/api/v1/tasks/' . $task->id);

        $response->assertStatus(204);

        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
