<?php
namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Team;
use App\Models\Project;
use App\Trait\ApiResponse;
use Illuminate\Support\Str;
use App\Http\Requests\FileRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\File;

class ProjectController extends Controller
{
    use ApiResponse;
    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();

        Project::create($validated);
        return $this->success('Project has been added successfully',null,201);
    }
    public function index()
    {
        $projects = Project::with('teams')->withCount('files')->get();
        if ($projects->isEmpty()) {
            return $this->error('No projects found at the moment.');
        }
        return $this->success('All projects retrieved successfully.', $projects);
    }



    public function show($id)
    {
        $project = Project::with('teams')->withCount('files')->find($id);
        if (! $project) {
            return $this->error('Project not found.');
        }
        return $this->success('Project with its team retrieved successfully.',$project);
    }

    public function storeFiles(FileRequest $request, $id)
    {
        $validated = $request->validated();
        $project   = Project::find($id);

        if (! $project) {
            return $this->error('Project not found');
        }

        unset($validated['files']);

        foreach ($request->file('files') as $file) {
            $filename = Str::uuid() . '.' . time() . '.' . $file->getClientOriginalExtension();
            $path= $file->storeAs('projects', $filename, ['disk' => config('filesystems.default')]);

            $project->files()->create(array_merge($validated, [
                'url' => $path,
            ]));
        }

        return $this->success('File Uploaded Successfully',null,201);
    }



    
    public function getprojectwithtasks($id){
        $project=Project::find($id);
         if(!$project){
            return $this->error('No Project Found');
        }
        $stats=[
            'todo'=>Task::where('project_id',$id)->where('status','todo')->get(),
            'Inprogress'=>Task::where('project_id',$id)->where('status','progress')->get(),
            'Inreview'=>Task::where('project_id',$id)->where('status','review')->get(),
            'Completed'=>Task::where('project_id',$id)->where('status','completed')->get(),
        ];
        $projectname=$project->name;
            $Data=[
                'Projectname'=>$projectname,
                'Stats'=>$stats
            ];


        return $this->success('This is Project with Tasks',$Data);
    }

    public function getprojectwithteams($id){
        $project=Project::find($id);
        if(!$project){
            return $this->error('No Project Found');
        }
        $teams=Team::where('project_id',$id)->get();
        $projectname=$project->name;

        $Data=[
            'projectname'=>$projectname,
            'teams'=>$teams
        ];
        return $this->success('This is Project with Team',$$Data);
    }

    public function getprojectwithfiles($id){
           $project=Project::find($id);
        if(!$project){
            return $this->error('No Project Found');
        }
        $recentfiles=File::where('fileable_id',$id)->latest()->take(3)->get();
        $files=File::where('fileable_id',$id)->get();
        $projectname=$project->name;
        $Data=[
            'projectname'=>$projectname,
            'Recent_files'=>$recentfiles,
            'All_files'=>$files,
        ];
    return $this->success('This is Project with Files',$Data);
    }
}
