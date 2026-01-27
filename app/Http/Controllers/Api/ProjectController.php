<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Trait\ApiResponse;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    use ApiResponse;
    public function store(ProjectRequest $request)
    {
        $validated = $request->validated();

        Project::create($validated);
        return $this->create('Project has been added successfully');
    }
    public function index()
    {
        $projects = Project::all();
        if ($projects->isEmpty()) {
            return $this->error('No projects found at the moment.');
        }
        return $this->returndata('All projects retrieved successfully.', $projects);
    }
    public function show($id)
    {
        $project = Project::find($id);
        if (! $project) {
            return $this->error('Project not found.');
        }

        return $this->returndata('Project retrieved successfully.', $project);
    }

    public function getProjectsWithteams()
    {
        $projects = Project::with('teams')->get();
        if ($projects->isEmpty()) {
            return $this->error('No projects with teams found.');
        }
        return $this->returndata('Projects with their teams retrieved successfully.', $projects);
    }
    public function getOneProjectWithteam($id)
    {
        $project = Project::with('teams', 'files')->find($id);
        if (! $project) {
            return $this->error('Project not found.');
        }
        return $this->success('Project with its team retrieved successfully.');
    }
    public function destroy($id)
    {
        $project = Project::find($id);
        if (! $project) {
            return $this->error('Project not found, nothing to delete.');
        }
        $project->delete();
        return $this->success('Project deleted successfully');
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

        return $this->create('File Uploaded Successfully');
    }
}
