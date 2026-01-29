<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = Task::with([
            'project',
            'comments',
            'files',
        ])->latest();

        if ($request->filled('search')) {
            $tasks->whereFullText(['name', 'description'], $request->search);
        }

        $tasks = $tasks->latest()->get();

        return response()->json([
            'message' => 'Tasks retrieved successfully',
            'data'    => $tasks
        ]);
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
        return response()->json($task);
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

        return response()->json([
            'data' => $tasks
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
