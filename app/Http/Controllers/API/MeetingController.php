<?php

namespace App\Http\Controllers\API;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\MeetingRequest;
use App\Http\Resources\MeetingResource;
use App\Models\Meeting;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $meetings = Meeting::with('users')->get();
        return $this->success(MeetingResource::collection($meetings),
            'Meetings retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
 public function store(MeetingRequest $request)
{
    $data = $request->validated();

    $users = $data['users'];
    unset($data['users']);

    $meeting = Meeting::create($data);
    $meeting->users()->attach($users);



    return $this->success(new MeetingResource($meeting->load('users')),
        'Meeting created successfully', 201);


}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
