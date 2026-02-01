<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with([
            'project',
            'comments',
            'files',
        ])->latest()->get();


        return TaskResource::collection($tasks);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $taskData = $request->except('files');

        $task = Task::create($taskData);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $path = $file->store('tasks', 'public');

                $task->files()->create([
                    'url' => Storage::url($path),
                ]);
            }
        }



        return response()->json([
            'message' => 'This task created successfully',
            'data' => $task->load('files'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load([
            'project',
            'comments',
            'comments.user', // لو الكومنت له يوزر
            'files',
        ]);
        return new TaskResource($task);

    }

    /**
     * Update the specified resource in storage.
     */

    public function searchAnyTask(Request $request)
    {

        $request->validate([
            'q' => 'required|string|min:2',
        ]);
        $q = $request->query('q');

        $tasks = Task::whereFullText(['name', 'description'], $q)->with('files')->get();

        return TaskResource::collection($tasks);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
