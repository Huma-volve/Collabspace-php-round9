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
use Illuminate\Support\Facades\DB;
class ApiDashboardController extends Controller
{
    use ApiResponser;

    /* ===============================
       TASK STATS
    =============================== */
    public function task_stats()
    {
        try {
            // عدد المهام حسب الحالة
            $pending     = Task::where('status', 'todo')->count();
            $inProgress  = Task::where('status', 'progress')->count();
            $completed   = Task::where('status', 'completed')->count();
            // نسبة إكمال المهام
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
            // البروجكتس النشطة مع إحصائيات التاسكات
            $projects = Project::where('status', 1)
                ->with('tasks')
                ->get()
                //transform بتعدي علي كل بروجكت واحدة واحدة 
    // تسمحلك تعدلها قبل ما ترجعها من غير ما تلمس الداتابيز
                ->transform(function ($project) {
                //حساب إجمالي وعدد التاسكات المكتملة في البروجكت دا
                    $totalTasks = $project->tasks->count();
                    //نحسب عدد التاسكات اللي حالتها completed
                    $completedTasks = $project->tasks
                        ->where('status', 'completed')
                        ->count();  
                
                // نسبة إكمال المشروع
                    $project->completed_tasks = $completedTasks;
                    $project->total_tasks = $totalTasks;
                     // لو عدد التاسكات الكلي اكبر من 0 احسب نسبة الاكمال غير كدة خلي النسبة 0
                    $project->completion_rate = $totalTasks > 0
                    //round بتقرب الرقم لعدد معين من الكسور العشرية (2 هنا)
                        ? round(($completedTasks / $totalTasks) * 100, 2)
                        //لو الشرط مش متحقق خلي النسبة 0
                        : 0;

            // نخفّي التاسكات لو الداشبورد مش محتاجها
            // ليه بنستعمل unset ؟
            // عشان ما نبعتش بيانات زيادة في الريسبونس مالهاش لازمة
            //هو محتاح فقط عدد التاسكات, اللي خلص , نسبة الاكمال بتاعتها
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
            // لو view=all نجيب كل التاسكات
            $limit = $request->query('view') === 'all' ? null : 5;
            // نجيب التاسكات (مش completed) ونرتبها حسب deadline
            $query = Task::where('status', '!=', 'completed')
                ->orderBy('end_date');

            if ($limit) {
                $query->take($limit);
            }

            $tasks = $query->get()
                ->transform(function ($task) {

            // =========================
            // due_label للـ UI
            // =========================
                    $task->due_label = Carbon::parse($task->end_date)->isToday()
                        ? 'Today'
                        : Carbon::parse($task->end_date)->format('M d');

                    // time-based progress rate
                    $start = Carbon::parse($task->start_date);
                    $end   = Carbon::parse($task->end_date);
                    $today = Carbon::today();

                    if ($today->lessThan($start)) {
                        // لو لسه ما بدأش
                        $task->progress_rate = 0;
                    } elseif ($today->greaterThanOrEqualTo($end)) {
                        // لو وصل أو عدّى ال deadline 
                        $task->progress_rate = 100;
                    } else {
                        //لو التاسك بدا بالفعل و لسة موصلش لل deadline
                        //التاسك شغال دلوقتي in progress
                        //diffInDays بيحسب اجمالي مدة التاسك بالايام
                        //من start_date لحد end_date
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
            // بنجيب الاجتماعات اللي تاريخها النهاردة أو بعد كده
            $meetings = Meeting::with('users')
                ->whereDate('date', '>=', Carbon::today())
                // نرتب الاجتماعات حسب التاريخ الأقرب
                ->orderBy('date')
                // ولو في أكتر من اجتماع في نفس اليوم نرتبهم حسب وقت البداية
                ->orderBy('start_time')
                // نجيب أول 5 اجتماعات بس (فيه زر View All)
                ->take(5)
                ->get()
                // نبدأ نعدّل كل Meeting واحدة واحدة من غير ما نلمس الداتابيز
                ->transform(function ($meeting) {
                // date_label و time_label للـ UI
                    $meeting->date_label = $meeting->date->format('d M Y');
                // Time range من وقت البداية للنهاية
                    $meeting->time_label =
                        Carbon::parse($meeting->start_time)->format('h:i A')
                        . ' - ' .
                        Carbon::parse($meeting->end_time)->format('h:i A');
                    // =========================
            // أعضاء الاجتماع (التيم)
            // =========================
                    $meeting->team_members = $meeting->users
                        ->pluck('full_name')
                        ->values();
                    // ننضف الريسبونس
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
            // بنجيب كل التيمز مع المشاريع + الليدر + اليوزرز + التاسكات
            $teams = Team::with(['projects', 'leader', 'users.tasks'])
                ->get()
                ->transform(function ($team) {
            // اسم الليدر
                    $team->leader_name = $team->leader?->full_name;
            // إحصائيات التاسكات
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
             // =========================
            // أنواع المشاريع
            // =========================
                    $team->project_types = $team->projects
                        ->groupBy('type')
                        ->map(fn ($projects) => $projects->count());
            // إجمالي عدد المشاريع
                    $team->total_projects = $team->projects->count();
            // تنظيف الريسبونس
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
            // أحدث 5 ملفات مرفوعة + اليوزر + الحاجة التابعة ليها
            $files = File::with(['uploader', 'fileable'])
                ->latest()
                ->take(5)
                ->get()
                ->transform(function ($file) {
                  // اسم العنصر اللي الفايل تابع ليه
                    $attachedName = null;

                    if ($file->fileable) {
                        if ($file->fileable instanceof Project) {
                            $attachedName = $file->fileable->name;
                        } elseif ($file->fileable instanceof Task) {
                            $attachedName = $file->fileable->name;
                        } elseif ($file->fileable instanceof Meeting) {
                            $attachedName = $file->fileable->subject;
                        }
                    }

                    return [
                        'id' => $file->id,
                        'file_name' => basename($file->url),
                        'file_type' => strtoupper(pathinfo($file->url, PATHINFO_EXTENSION)),
                        'attached_to' => class_basename($file->fileable_type),
                        // اسم المشروع / التاسك / الميتنج
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
public function projectsOverview(Request $request)
{
    try {
        // السنة (default = السنة الحالية)
        $year  = (int) $request->query('year', now()->year);

        // الشهر (اختياري)
        $month = $request->query('month');

        // =========================
        // Per Year
        // =========================
        $perYear = Project::whereYear('created_at', $year)->count();

        // =========================
        // Per Month (MySQL + SQLite)
        // =========================
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $perMonth = Project::selectRaw("strftime('%m', created_at) as month, COUNT(*) as total")
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
        } else {
            // MySQL
            $perMonth = Project::selectRaw("MONTH(created_at) as month, COUNT(*) as total")
                ->whereYear('created_at', $year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month');
        }

        // =========================
        // Per Day
        // =========================
        if ($month) {

            if ($driver === 'sqlite') {
                $perDay = Project::selectRaw("strftime('%d', created_at) as day, COUNT(*) as total")
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('total', 'day');
            } else {
                // MySQL
                $perDay = Project::selectRaw("DAY(created_at) as day, COUNT(*) as total")
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->groupBy('day')
                    ->orderBy('day')
                    ->pluck('total', 'day');
            }

        } else {
            // اليوم الحالي فقط
            $perDay = Project::whereDate('created_at', now()->toDateString())->count();
        }

        return $this->success([
            'year'      => $year,
            'per_year'  => $perYear,
            'per_month' => $perMonth,
            'per_day'   => $perDay,
        ], 'Projects overview fetched successfully');

    } catch (\Throwable $e) {
        return $this->error(
            'Failed to fetch projects overview',
            ['exception' => $e->getMessage()],
            500
        );
    }
}
}
