<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Filters\ProjectFilter;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new ProjectFilter();
        $queryItems = $filter->transform($request);

        $query = Project::where('user_id', Auth::id());

        if(count($queryItems) > 0){
            $query->where($queryItems);
        }

        return new ProjectCollection($query->paginate(4));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        
        $task = Project::create($validated);

        return response()->json(
        ['message' => 'Project created successfully',
            'data' => $task
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = Project::findOrFail($id);

        if (Gate::denies('view-project', $project)) {
            return response()->json([
                'message' => 'Access denied.'
            ], 403);
        }

        if(!$project){
            return response()->json(['message' => 'Project not found'], 404);
        }


        return new ProjectResource($project);
    }

    public function showWithTasks(string $id){
        $project = Project::with('tasks')
                  ->where('id', $id)
                  ->first();

        if (Gate::denies('view-project', $project)) {
            return response()->json([
                'message' => 'Access denied.'
            ], 403);
        }
        
        if(!$project){
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $id)
    {
        $project = Project::findOrFail($id);

        if (! Gate::allows('update-project', $project)) {
            return response()->json([
                'message' => 'Project not found or access denied'
            ], 403);
        }

        $validated = $request->validated();
        $project->update($validated);

        return response()->json([
            'message' => 'Project updated successfully',
            'data' => $project,
        ], 200);
    }
}
