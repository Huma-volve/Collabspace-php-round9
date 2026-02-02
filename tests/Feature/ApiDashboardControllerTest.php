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
    //بيستخدم factory بيعمل تاسك واحدة todo
    Task::factory()->create(['status' => 'todo']);
    //بيستخدم factory بيعمل تاسك واحدة progress
    Task::factory()->create(['status' => 'progress']);
    //بيستخدم factory بيعمل تاسك واحدة completed
    Task::factory()->create(['status' => 'completed']);

    //بيعمل طلب GET Request 
    //متوقع json response
    //بيروح على ال api/dashboard/task_stats
    $response = $this->getJson('/api/dashboard/task_stats');

    //assertStatus(200) الapi اشتغل تمام
    $response->assertStatus(200)
        ->assertJsonFragment([
            'pending_tasks' => 1,
            'in_progress_tasks' => 1,
            'completed_tasks'   => 1,
            'completion_rate'   => 33.33,
        ]);
}
/** @test */
public function test_it_returns_active_projects_with_stats()
{
    //بيستخدم factory بيعمل project واحدة status = 1 (active)
    $project = Project::factory()->create(['status' => 1]);

    //إنشاء 2Tasks مكتملة
    Task::factory()->count(2)->create([
        'project_id' => $project->id,
        'status' => 'completed',
    ]);
    //انشاء 1 task غير مكتملة
    Task::factory()->create([
        'project_id' => $project->id,
        'status' => 'todo',
    ]);
    //كدة 3 tasks في المشروع

    //Get Request 
    //بيرجع projects نشطة مع احصائياتها
    $response = $this->getJson('/api/dashboard/projects');

    //assertStatus(200) الapi اشتغل تمام
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
    //انشاء task واحدة بحالة todo
    //بدات من يومين و هتنتهي بعد يومين
    Task::factory()->create([
        'status' => 'todo',
        'start_date' => now()->subDays(2),
        'end_date' => now()->addDays(2),
    ]);
    //Get Request 
    //بيرجع computed tasks مع due label و progress rate
    $response = $this->getJson('/api/dashboard/tasks');
    //الassertStatus(200) الapi اشتغل تمام
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
public function test_it_returns_upcoming_meetings()
{
    //انشاء meeting واحدة بموعد غدا
    //من الساعة 10 الصبح للساعة 11 الصبح
    $meeting = Meeting::factory()->create([
        'date' => Carbon::tomorrow(),
        'start_time' => '10:00',
        'end_time' => '11:00',
    ]);
    //Get Request
    //بيرجع upcoming meetings
    $response = $this->getJson('/api/dashboard/meetings');

    //الserver اشتغل تمام
    $response->assertStatus(200)
    
    //الjson structure المتوقع
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
    //انشاء team واحدة
    $team = Team::factory()->create();
    //انشاء leader للفريق
    $leader = User::factory()->create(['team_id' => $team->id]);
    //ربط القائد بالفريق
    $team->leader()->associate($leader)->save();
    //انشاء عضو بالفريق 
    $user = User::factory()->create(['team_id' => $team->id]);
    //انشاء 2 مشاريع للفريق
    Task::factory()->count(2)->create([
        'project_id' => Project::factory(),
        'status' => 'completed',
    ]);
    //Get Request
    //بيرجع الفرق مع اعضاءها و احصائيات المشاريع
    $response = $this->getJson('/api/dashboard/teams');
    //الserver اشتغل تمام
    $response->assertStatus(200)
    //الjson structure المتوقع
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
    //انشاء user
    $user = User::factory()->create();
    //انشاء project
    $project = Project::factory()->create();
    //انشاء ملف مرتبط بالمشروع و المرفوع من قبل الuser
    File::factory()->create([
        'uploader_id' => $user->id,
        'fileable_id' => $project->id,
        'fileable_type' => Project::class,
    ]);
    //Get Request
    //بيرجع الملفات المرفوعة مؤخرا
    $response = $this->getJson('/api/dashboard/files');

    //الserver اشتغل تمام
    $response->assertStatus(200)
    //الjson structure المتوقع
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
