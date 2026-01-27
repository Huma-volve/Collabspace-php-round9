<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\Team;
use App\Models\File;
use Carbon\Carbon;
use App\Traits\ApiResponser;

class ApiDashboardController extends Controller
{
    use ApiResponser;

    /* ===============================
       TASK STATS
    =============================== */
    public function task_stats()
    {
        try {
            $pending     = Task::where('status', 'todo')->count();
            $inProgress  = Task::where('status', 'progress')->count();
            $completed   = Task::where('status', 'completed')->count();

            $totalTasks = Task::count();
            $completionRate = $totalTasks > 0
                ? round(($completed / $totalTasks) * 100, 2)
                : 0;

            return $this->success([
                'pending_tasks'     => $pending,
                'in_progress_tasks' => $inProgress,
                'completed_tasks'   => $completed,
                'completion_rate'   => $completionRate,
            ], 'Task statistics fetched successfully');

        } catch (\Throwable $e) {
            return $this->error(
                'Failed to fetch task statistics',
                ['exception' => $e->getMessage()],
                500
            );
        }
    }

    /* ===============================
       PROJECTS
    =============================== */
    public function projects()
    {
        try {
            $projects = Project::where('status', 1)
                ->with('tasks')
                ->get()
                ->transform(function ($project) {

                    $totalTasks = $project->tasks->count();
                    $completedTasks = $project->tasks
                        ->where('status', 'completed')
                        ->count();

                    $project->completed_tasks = $completedTasks;
                    $project->total_tasks = $totalTasks;
                    $project->completion_rate = $totalTasks > 0
                        ? round(($completedTasks / $totalTasks) * 100, 2)
                        : 0;

                    unset($project->tasks);

                    return $project;
                });

            return $this->success(
                $projects,
                'Active projects fetched successfully'
            );

        } catch (\Throwable $e) {
            return $this->error(
                'Failed to fetch projects',
                ['exception' => $e->getMessage()],
                500
            );
        }
    }

    /* ===============================
       TASKS
    =============================== */
    public function tasks(Request $request)
    {
        try {
            $limit = $request->query('view') === 'all' ? null : 5;

            $query = Task::where('status', '!=', 'completed')
                ->orderBy('end_date');

            if ($limit) {
                $query->take($limit);
            }

            $tasks = $query->get()
                ->transform(function ($task) {

                    // due label
                    $task->due_label = Carbon::parse($task->end_date)->isToday()
                        ? 'Today'
                        : Carbon::parse($task->end_date)->format('M d');

                    // time-based progress rate
                    $start = Carbon::parse($task->start_date);
                    $end   = Carbon::parse($task->end_date);
                    $today = Carbon::today();

                    if ($today->lessThan($start)) {
                        $task->progress_rate = 0;
                    } elseif ($today->greaterThanOrEqualTo($end)) {
                        $task->progress_rate = 100;
                    } else {
                        $totalDays  = $start->diffInDays($end);
                        $passedDays = $start->diffInDays($today);

                        $task->progress_rate = $totalDays > 0
                            ? round(($passedDays / $totalDays) * 100)
                            : 0;
                    }

                    return $task;
                });

            return $this->success(
                $tasks,
                'Tasks fetched successfully'
            );

        } catch (\Throwable $e) {
            return $this->error(
                'Failed to fetch tasks',
                ['exception' => $e->getMessage()],
                500
            );
        }
    }

    /* ===============================
       UPCOMING MEETINGS
    =============================== */
    public function upcomingMeetings()
    {
        try {
            $meetings = Meeting::with('users')
                ->whereDate('date', '>=', Carbon::today())
                ->orderBy('date')
                ->orderBy('start_time')
                ->take(5)
                ->get()
                ->transform(function ($meeting) {

                    $meeting->date_label = $meeting->date->format('d M Y');

                    $meeting->time_label =
                        Carbon::parse($meeting->start_time)->format('h:i A')
                        . ' - ' .
                        Carbon::parse($meeting->end_time)->format('h:i A');

                    $meeting->team_members = $meeting->users
                        ->pluck('full_name')
                        ->values();

                    unset($meeting->users);

                    return $meeting;
                });

            return $this->success(
                $meetings,
                'Upcoming meetings fetched successfully'
            );

        } catch (\Throwable $e) {
            return $this->error(
                'Failed to fetch meetings',
                ['exception' => $e->getMessage()],
                500
            );
        }
    }

    /* ===============================
       TEAMS
    =============================== */
    public function teams()
    {
        try {
            $teams = Team::with(['projects', 'leader', 'users.tasks'])
                ->get()
                ->transform(function ($team) {

                    $team->leader_name = $team->leader?->full_name;

                    $team->team_members = $team->users->map(function ($user) {
                        $totalTasks = $user->tasks->count();
                        $completedTasks = $user->tasks
                            ->where('status', 'completed')
                            ->count();

                        return [
                            'id' => $user->id,
                            'name' => $user->full_name,
                            'role' => $user->id === optional($user->team)->leader_id
                                ? 'leader'
                                : 'member',
                            'total_tasks' => $totalTasks,
                            'completed_tasks' => $completedTasks,
                        ];
                    });

                    $team->project_types = $team->projects
                        ->groupBy('type')
                        ->map(fn ($projects) => $projects->count());

                    $team->total_projects = $team->projects->count();

                    unset($team->projects, $team->leader, $team->users);

                    return $team;
                });

            return $this->success(
                $teams,
                'Teams fetched successfully'
            );

        } catch (\Throwable $e) {
            return $this->error(
                'Failed to fetch teams',
                ['exception' => $e->getMessage()],
                500
            );
        }
    }

    /* ===============================
       FILES
    =============================== */
    public function files()
    {
        try {
            $files = File::with(['uploader', 'fileable'])
                ->latest()
                ->take(5)
                ->get()
                ->transform(function ($file) {

                    $attachedName = null;

                    if ($file->fileable) {
                        if ($file->fileable instanceof \App\Models\Project) {
                            $attachedName = $file->fileable->name;
                        } elseif ($file->fileable instanceof \App\Models\Task) {
                            $attachedName = $file->fileable->name;
                        } elseif ($file->fileable instanceof \App\Models\Meeting) {
                            $attachedName = $file->fileable->subject;
                        }
                    }

                    return [
                        'id' => $file->id,
                        'file_name' => basename($file->url),
                        'file_type' => strtoupper(pathinfo($file->url, PATHINFO_EXTENSION)),
                        'attached_to' => class_basename($file->fileable_type),
                        'attached_name' => $attachedName,
                        'uploaded_at' => $file->created_at->format('d M Y'),
                        'uploaded_by' => $file->uploader?->full_name,
                        'url' => $file->url,
                    ];
                });

            return $this->success(
                $files,
                'Recent files fetched successfully'
            );

        } catch (\Throwable $e) {
            return $this->error(
                'Failed to fetch files',
                ['exception' => $e->getMessage()],
                500
            );
        }
    }
}
