<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

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
        $validated = $request->validated();

        $validated['priority'] ??= 'low';
        $validated['status'] ??= 'todo';


        $task = Task::create($validated);

        return response()->json([
            'message' => 'This task created successfully',
            'data' => $task,
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
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
