<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class DashboardController extends Controller
{
    use ApiResponse;

    public function index(){
        $stats=[
            'todo'=>Task::where('status','todo')->count(),
            'progress' => Task::where('status', 'progress')->count(),
            'completed' => Task::where('status', 'completed')->count(),
        ];

        return $this->success('Dashboard Data',$stats);
    }
    public function ActiveProject(){
        $projects=Project::where('status',1)->limit(2)->get();
        if($projects->isEmpty()){
            return $this->error('No Active Projects Found');
        }
        return $this->success('This is active Projects',$projects);
    }
    public function recentfiles(){
        $files=File::latest()->take(3)->get();
        if($files->isEmpty()){
            return $this->error('No Files Found');
        }
        return $this->success('This is Files',$files);
    }
    public function Tasks(){
        $tasks=Task::latest()->take(3)->get();
        if($tasks->isEmpty()){
            return $this->error('No Tasks Found');
        }
        return $this->success('This is latest tasks',$tasks);
    }
public function tas($id){
    $project=Project::find($id);
    if($project){
         $projectname=$project->name;
    }

   $projects=Project::with(['tasks'=>function($q){
        $q->where('priority','high');
    }])->get();
    return $this->success('This is Project With  high tasks ',$projects);
}


public function getallproject(){
    $projects=Project::with('tasks')->get();
    $projects=Project::with(['tasks'=>function($q){
        $q->where('status',1);
    }])->get();

}
public function oneproject($id){
$project=Project::find($id);
if(!$project){
    return $this->error('not found project');
}
$projectname=$project->name;
return $this->success('This is name of project',$projectname);
}

public function getonefile($id){
    $project=Project::pluck('name');
    $project=Project::with('files')->get('name');
    $projectname=Project::find($id)->name;
    $project=Project::where('id',$id)->value('name');
    if(!$project){
        return $this->error('not found');
    }
    return $this->success('this is',$project);

}

public function getAll(){
    $projects=Project::all();
    if($projects->isEmpty()){
        return $this->error('Not found This project');
    }
    return $this->success('This is Projects',$projects);
}
public function getoneproject($id){
$project=Project::find($id);
if(!$project){
    return $this->error('No Project found');
}
return $this->success('This is project',$project);
}
public function ProjectsWithTasks(){
    $projects=Project::with('tasks')->pluck('name');
     if($projects->isEmpty()){
        return $this->error('Not found This project');
    }
    return $this->success('This is Projects',$projects);
}
public function nameoftask(){
    $projects=Project::with(['tasks'=>function($query){
        $query->where('status','inprogress');
    }]);

      if($projects->isEmpty()){
        return $this->error('Not found This project');
    }
    return $this->success('This is Projects',$projects);
}
public function getfiles(){
    $projects=Project::has('files')->with('files')->orderBy('files_count','desc')->get();
     if($projects->isEmpty()){
        return $this->error('Not found This project');
    }
    return $this->success('This is Projects',$projects);
}
public function gg($id){
    $pr=Project::with(['tasks'=>function($q){
        $q->where('status','active');
    }])->find($id);
    if(!$pr){
        return $this->error('Not Found This Project');
    }
    return $this->success('This is project',$pr);
}
public function fares($id){
$project=Project::find($id);
if(!$project){
    return $this->error('No found Project');
}
$projectname=$project->name;
return $this->success('This is project',$projectname);

}
}


