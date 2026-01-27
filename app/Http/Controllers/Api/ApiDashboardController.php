<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\Meeting;
use App\Models\Team;
use App\Models\File;
use Illuminate\Support\Str;

class ApiDashboardController extends Controller
{
public function task_stats()
{
    // عدد المهام حسب الحالة
    $pending = Task::where('status', 'todo')->count();
    $inProgress = Task::where('status', 'progress')->count();
    $completed = Task::where('status', 'completed')->count();
// نسبة إكمال المهام
    $totalTasks = Task::count();
    $completionRate = $totalTasks > 0
        ? round(($completed / $totalTasks) * 100, 2)
        : 0;

    return response()->json([
        'status' => true,
        'stats' => [
            'pending_tasks' => $pending,
            'in_progress_tasks' => $inProgress,
            'completed_tasks' => $completed,
            'completion_rate' => $completionRate
        ]
    ], 200);
}
public function projects()
{
    // البروكتس النشطة مع إحصائيات التاسكات
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

    return response()->json([
        'status' => true,
        'projects' => $projects
    ], 200);
}
public function tasks(Request $request)
{
    // لو view=all نجيب كل التاسكات
    $limit = $request->query('view') === 'all' ? null : 5;
// التاسكات الغير مكتملة مرتبة حسب تاريخ النهاية
    $query = Task::where('status', '!=', 'completed')
        ->orderBy('end_date');
// نطبق الحد الاقصى لو في
    if ($limit) {
        $query->take($limit);
    }
//بيرجع كولكشن اوف المهام
    $tasks = $query->get()
    //transform بتعدي علي كل تاسك واحدة واحدة و تسمحلك تعدلها قبل ما ترجعها من غير ما تلمس الداتابيز
        ->transform(function ($task) {
            //بتضيف attribute جديد اسمه due_label
            // due_label مش موجود في الجدول معمول خصيصا لل UI
            //لو end_date هو اليوم يرجع Today
            //غير كده يرجع التاريخ بشكل منسق
            $task->due_label = Carbon::parse($task->end_date)->isToday()
                ? 'Today'
                : Carbon::parse($task->end_date)->format('M d');

            return $task;
        });

    return response()->json([
        'status' => true,
        'tasks' => $tasks
    ], 200);
}
public function dailyTasksCompletionRate()
{
    $tasks = Task::selectRaw('
            DATE(updated_at) as date,
            COUNT(*) as total_tasks,
            SUM(status = "completed") as completed_tasks
        ')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->map(function ($day) {
            $day->completion_rate = $day->total_tasks > 0
                ? round(($day->completed_tasks / $day->total_tasks) * 100, 2)
                : 0;

            return $day;
        });

    return response()->json([
        'status' => true,
        'daily_completion_rate' => $tasks
    ], 200);
}

public function upcomingMeetings()
{
    //بنجيب الاجتماعات اللي تاريخها النهاردة او  بعد كدة
    $meetings = Meeting::whereDate('date', '>=', Carbon::today())
    // نرتب الاجتماعات حسب التاريخ الاقرب الاول
        ->orderBy('date')
    // لو في أكتر من اجتماع في نفس اليوم نرتبهم حسب وقت البداية
        ->orderBy('start_time')
    //نجيب اول 5 اجتماعات بس , فيه زر view all بعد كدة
        ->take(5)
        ->get()
    //نبدا نعدل كل ميتنج واحدة واحدة من غير ما نلمس الداتابيز  
        ->transform(function ($meeting) {

            // Labels جاهزة للعرض في الداشبورد
            $meeting->date_label = $meeting->date->format('d M Y');
            //نعمل Time Range من وقت البداية لوقت النهاية
            $meeting->time_label =
                Carbon::parse($meeting->start_time)->format('h:i A')
                . ' - ' .
                Carbon::parse($meeting->end_time)->format('h:i A');
    //نرجع الاجتماع بعد التعديلات
            return $meeting;
        });

    return response()->json([
        'status' => true,
        'meetings' => $meetings
    ], 200);
}
public function teams()
{
    //بنجيب كل التيمز مع المشاريع والليدر بتاع كل تيم
    $teams = Team::with(['projects', 'leader'])
        ->get()
    //نبدا نعدل كل تيم واحدة واحدة من غير ما نلمس الداتابيز  

        ->transform(function ($team) {

            // اسم الليدر
            //لو مفيش ليدر يرع null
            $team->leader_name = $team->leader?->full_name;

            // أنواع المشاريع اللي شغال عليها التيم
            $team->project_types = $team->projects
            // groupBy بتجمع المشاريع حسب النوع
                ->groupBy('type')
                // map يحسب عدد المشاريع في كل نوع
                ->map(fn ($projects) => $projects->count());

            // إجمالي عدد المشاريع
            $team->total_projects = $team->projects->count();
            // unset بنشيل المشاريع والليدر من الريسبونس عشان مش محتاجينهم
            unset($team->projects, $team->leader);

            return $team;
        });

    return response()->json([
        'status' => true,
        'teams' => $teams
    ], 200);
}

public function files()
{
    // أحدث 5 ملفات مرفوعة
    $files = File::latest()
        ->take(5)
        ->get()
    //نبدا نعدل كل فايل واحد واحد من غير ما نلمس الداتابيز  

        ->transform(function ($file) {

            return [
                // رقم الملف (ممكن الفرونت يحتاجه كـ key)
                'id' => $file->id,
                // رقم الملف (ممكن الفرونت يحتاجه كـ key)
                'file_name' => basename($file->url),
                //نوع الملف (الامتداد) و بنحوله كابيتال PDF, DOCX, FIG عشان يظهر في الداشبورد
                'file_type' => strtoupper(pathinfo($file->url, PATHINFO_EXTENSION)),
                //نعرف الملف تابع ل ايه :project, task, meeting
                'attached_to' => class_basename($file->fileable_type),
                // التاريخ اللي اترفع فيه الملف بشكل منسق
                'uploaded_at' => $file->created_at->format('d M Y'),
                'url' => $file->url, // رابط اختياري لو هتفتح الملف
            ];
        });

    return response()->json([
        'status' => true,
        'files' => $files
    ], 200);
}

}
