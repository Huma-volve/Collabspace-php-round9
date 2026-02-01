<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_projects_overview()
    {
        // Arrange
        Project::factory()->create([
            'created_at' => now(),
        ]);

        Project::factory()->create([
            'created_at' => now()->subMonth(),
        ]);

        // Act
        $response = $this->getJson('/api/projects-overview');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'year',
                    'per_year',
                    'per_month',
                    'per_day',
                ],
            ]);
    }
    /** @test */
public function it_returns_task_statistics()
{
    \App\Models\Task::factory()->create(['status' => 'todo']);
    \App\Models\Task::factory()->create(['status' => 'completed']);

    $response = $this->getJson('/api/dashboard/task-stats');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'pending_tasks' => 1,
            'completed_tasks' => 1,
        ]);
}

}
