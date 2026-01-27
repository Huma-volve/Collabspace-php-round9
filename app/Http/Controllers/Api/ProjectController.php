<?php
namespace App\Http\Controllers\Api;

use App\Models\Project;
use App\Trait\ApiTrait;
use App\Trait\ApiResponse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;

class ProjectController extends Controller
{
    use ApiResponse;
    public function addproject(ProjectRequest $request)
    {
        $validated = $request->validated();

        Project::create($validated);
        return $this->create('Project has been added successfully');
    }
    public function getAllprojects()
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return $this->success('No projects found at the moment.');
        }
        return $this->returndata('All projects retrieved successfully.', $projects);
    }
    public function getOneproject($id)
    {
        $project = Project::find($id);
        if (! $project) {
            return $this->success('Project not found.');
        }

        return $this->returndata('Project retrieved successfully.', $project);
    }

    public function getProjectsWithteams()
    {
        $projects = Project::with('teams')->get();
        if ($projects->isEmpty()) {
            return $this->success('No projects with teams found.');
        }
        return $this->returndata('Projects with their teams retrieved successfully.', $projects);
    }
    public function getOneProjectWithteam($id)
    {
        $project = Project::with('teams')->findOrFail($id);
        if (! $project) {
            return $this->success('Project not found.');
        }
        return $this->success('Project with its team retrieved successfully.');
    }
    public function deleteproject($id){
        $project=Project::find($id);
        if(!$project){
            return $this->success('Project not found, nothing to delete.');
        }
        $project->delete();
        return $this->success('Project deleted successfully');
    }
    public function storeFiles(Request $request,$id){
        $project=Project::find($id);
        foreach($request->file('files') as $file){
            $filename=Str::uuid().'.'.time().'.'.$file->getClientOriginalExtension();
            $path=$file->storeAs('projects',$filename,['disk'=>'Files']);
            $project->files()->create([
                'url'=>$path
            ]);
        }
       return $this->create('File Uploaded Successfully');
    }
}
