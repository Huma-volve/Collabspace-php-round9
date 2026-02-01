<?php

use App\Models\Task;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

public function test_returns_list_of_tasks()
{
        Task::factory()->count(3)->create();

        $this->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_stores_a_task_with_files()
    {
        Storage::fake('public');

        $response = $this->postJson('/api/tasks', [
            'name' => 'New Task',
            'description' => 'Test desc',
            'priority' => 'high',
            'status' => 'todo',
            'files' => [
                UploadedFile::fake()->image('1.png'),
                UploadedFile::fake()->image('2.png'),
            ],
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('tasks', ['name' => 'New Task']);
        $this->assertDatabaseCount('files', 2);
    }

    public function test_shows_a_single_task()
    {
        $task = Task::factory()->create();

        $this->getJson("/api/tasks/{$task->id}")
            ->assertOk()
            ->assertJsonFragment([
                'name' => $task->name,
            ]);
    }

    public function test_searches_tasks_by_keyword()
    {
        Task::factory()->create(['name' => 'Laravel Task']);
        Task::factory()->create(['name' => 'Vue Task']);

        $this->getJson('/api/tasks/search?q=Laravel')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_fails_search_if_query_is_too_short()
    {
        $this->getJson('/api/tasks/search?q=a')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }

}
