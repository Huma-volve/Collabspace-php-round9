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

    // test to get all tasks
    public function test_returns_list_of_tasks()
    {
        Task::factory()->count(3)->create();

        $this->getJson('/api/tasks')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }
    
    // test to create new task
    public function test_stores_a_task_with_files()
    {
        Storage::fake('public');

        $project = Project::factory()->create();

        $response = $this->postJson('/api/task', [
            'name' => 'New Task',
            'description' => 'Test desc',
            'start_date' => now()->toDateString(),
            'end_date' => now()->addDay()->toDateString(),
            'priority' => 'high',
            'status' => 'todo',
            'project_id' => $project->id,
            'files' => [
                UploadedFile::fake()->image('1.png'),
            ],
        ]);

        $response->assertCreated()
            ->assertJsonFragment([
                'message' => 'This task created successfully',
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'New Task',
            'project_id' => $project->id,
        ]);


        $this->assertDatabaseCount('files', 1);

        $this->assertTrue(Storage::disk('public')->exists('tasks'));
    }

    // test to show single task
    public function test_shows_a_single_task()
    {
        $task = Task::factory()->create();

        $this->getJson("/api/task/{$task->id}")
            ->assertOk()
            ->assertJsonFragment([
                'name' => $task->name,
            ]);
    }

    // test to search any task by key words
    public function test_searches_tasks_by_keyword()
    {
        Task::factory()->create([
            'name' => 'Laravel Task',
            'description' => 'Test desc',
        ]);

        Task::factory()->create([
            'name' => 'Vue Task',
            'description' => 'Another desc',
        ]);

        $this->getJson('/api/tasks/search?q=Laravel')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Laravel Task');
    }

    public function test_fails_search_if_query_is_too_short()
    {
        $this->getJson('/api/tasks/search?q=a')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['q']);
    }
}
