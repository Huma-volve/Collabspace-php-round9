<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Meeting;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiDashboardControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_it_returns_projects_overview()
    {
        // Arrange
        Project::factory()->create([
            'created_at' => now(),
        ]);

        Project::factory()->create([
            'created_at' => now()->subMonth(),
        ]);

        // Act
        $response = $this->getJson('/api/dashboard/projectsOverview');

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
    'status',
    'code',
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
public function test_it_returns_task_statistics()
{
    \App\Models\Task::factory()->create(['status' => 'todo']);
    \App\Models\Task::factory()->create(['status' => 'completed']);

    $response = $this->getJson('/api/dashboard/task_stats');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'pending_tasks' => 1,
            'completed_tasks' => 1,
        ]);
}
/** @test */
public function test_it_returns_active_projects_with_stats()
{
    $project = Project::factory()->create(['status' => 1]);
    Task::factory()->count(2)->create([
        'project_id' => $project->id,
        'status' => 'completed',
    ]);
    Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'todo',
    ]);

    $response = $this->getJson('/api/dashboard/projects');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'code',
            'message',
            'data' => [
                [
                    'id',
                    'name',
                    'completed_tasks',
                    'total_tasks',
                    'completion_rate',
                ]
            ],
        ]);
}
/** @test */
public function test_it_returns_tasks_with_due_label_and_progress_rate()
{
    Task::factory()->create([
        'status' => 'todo',
        'start_date' => now()->subDays(2),
        'end_date' => now()->addDays(2),
    ]);

    $response = $this->getJson('/api/dashboard/tasks');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'code',
            'message',
            'data' => [
                [
                    'id',
                    'name',
                    'due_label',
                    'progress_rate',
                ]
            ],
        ]);
}
/** @test */
public function it_returns_upcoming_meetings()
{
    $meeting = Meeting::factory()->create([
        'date' => Carbon::tomorrow(),
        'start_time' => '10:00',
        'end_time' => '11:00',
    ]);

    $response = $this->getJson('/api/dashboard/meetings');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'code',
            'message',
            'data' => [
                [
                    'id',
                    'date_label',
                    'time_label',
                    'team_members',
                ]
            ],
        ]);
}
/** @test */
/** @test */
public function test_it_returns_teams_with_members_and_projects_stats()
{
    $team = Team::factory()->create();
    $leader = User::factory()->create(['team_id' => $team->id]);
    $team->leader()->associate($leader)->save();

    $user = User::factory()->create(['team_id' => $team->id]);

    Task::factory()->count(2)->create([
        'project_id' => Project::factory(),
        'status' => 'completed',
    ]);

    $response = $this->getJson('/api/dashboard/teams');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'code',
            'message',
            'data' => [
                [
                    'leader_name',
                    'team_members',
                    'project_types',
                    'total_projects',
                ]
            ],
        ]);
}

/** @test */
public function it_returns_recent_files()
{
    $user = User::factory()->create();
    $project = Project::factory()->create();

    File::factory()->create([
        'uploader_id' => $user->id,
        'fileable_id' => $project->id,
        'fileable_type' => Project::class,
    ]);

    $response = $this->getJson('/api/dashboard/files');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'status',
            'code',
            'message',
            'data' => [
                [
                    'id',
                    'file_name',
                    'file_type',
                    'attached_to',
                    'attached_name',
                    'uploaded_at',
                    'uploaded_by',
                    'url',
                ]
            ],
        ]);
}

}
