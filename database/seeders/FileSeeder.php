<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class FileSeeder extends Seeder
{
    public function run(): void
    {

Storage::put('files/project-specs.pdf', 'Dummy PDF content');
Storage::put('files/ui-design.fig', 'Dummy Figma content');
Storage::put('files/task-requirements.docx', 'Dummy Doc content');

        // نجيب كل اليوزرز
        $users = User::pluck('id')->toArray();

        if (empty($users)) {
            return;
        }

        // Files مرتبطة بـ Projects
        $project = Project::first();

        if ($project) {
            File::create([
                'url' => 'files/project-specs.pdf',
                'fileable_id' => $project->id,
                'fileable_type' => Project::class,
                'uploaded_by' => fake()->randomElement($users),
            ]);

            File::create([
                'url' => 'files/ui-design.fig',
                'fileable_id' => $project->id,
                'fileable_type' => Project::class,
                'uploaded_by' => fake()->randomElement($users),
            ]);
        }

        // Files مرتبطة بـ Tasks
        $task = Task::first();

        if ($task) {
            File::create([
                'url' => 'files/task-requirements.docx',
                'fileable_id' => $task->id,
                'fileable_type' => Task::class,
                'uploaded_by' => fake()->randomElement($users),
            ]);
        }
    }
}
