<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;
use App\Filters\UserFilter;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new UserFilter();
        $queryItems = $filter->transform($request);

        if(count($queryItems) == 0){
            return new UserCollection(User::paginate(4));
        } else {

            return new UserCollection(User::where($queryItems)->paginate(4));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $user = User::create($validated);

        return response()->json(
    ['message' => 'User created successfully',
            'data' => $user
            ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Fetch the user
        $user = User::findOrFail($id);

        // Authorization: check if this is the authenticated user
        if ($user->id !== Auth::id()) {
            return response()->json([
                'message' => 'Access denied. You can only view your own profile.'
            ], 403);
        }

        return new UserResource($user);
    }

    public function showWithProjects(string $id)
    {
        $user = User::with('projects')->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Authorization: check if the authenticated user matches the requested user
        if ($user->id !== Auth::id()) {
            return response()->json([
                'message' => 'Access denied. You can only view your own projects.'
            ], 403);
        }

        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();
        $user->update($validated);
        return response()->json($user, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
