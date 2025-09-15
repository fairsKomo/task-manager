<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TaskCollection;
use App\Models\Project;

class TaskController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        // Find the project
        $project = Project::find($validated['project_id']);

        if (!$project) {
            return response()->json([
                'message' => 'Project not found.'
            ], 404);
        }

        // Check authorization
        if (Gate::denies('create-task', $project)) {
            return response()->json([
                'message' => 'Access denied. You can only add tasks to your own projects.'
            ], 403);
        }

        // Create task
        $task = Task::create($validated);

        return response()->json([
            'message' => 'Task created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Load the task with its status and project relation
        $task = Task::with(['status', 'project'])->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found.'
            ], 404);
        }

        // Authorize: user can only view tasks for their own projects
        if (Gate::denies('view-task', $task->project)) {
            return response()->json([
                'message' => 'Access denied. You can only view tasks of your own projects.'
            ], 403);
        }

        return new TaskResource($task);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, string $id)
    {   
        $task = Task::with('project')->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Task not found.'
            ], 404);
        }

        if (Gate::denies('view-task', $task->project)) {
            return response()->json([
                'message' => 'Access denied. You can only Update tasks of your own projects.'
            ], 403);
        }

        $validated = $request->validated();
        $task->update($validated);
        return response()->json($task, 201);
    }

}
