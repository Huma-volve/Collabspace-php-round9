<?php

namespace App\Http\Controllers\Api;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentMeetingRequest;
use App\Http\Requests\MeetingRequest;
use App\Http\Resources\CommentMeetingResource;
use App\Http\Resources\MeetingResource;
use App\Models\CommentMeeting;
use App\Models\Meeting;
use App\Services\ZoomMeetingService;
use App\Services\ZoomTokenService;
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
        if($meetings->isEmpty()){
            return $this->success([],'No meetings found',200);
        }
        return $this->success(MeetingResource::collection($meetings),
            'Meetings retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(MeetingRequest $request, ZoomMeetingService $zoomService)
{
    $data = $request->validated();
    $users = $data['users'] ?? [];
    unset($data['users']);

    try {
        $zoomData = [
            'topic' => $data['subject'],
            'start_time' => $data['date'] . 'T' . $data['start_time'],
            'duration' => isset($data['duration']) ? $data['duration'] : 30,
            'agenda' => $data['note'] ?? '',
        ];

$zoomMeeting = $zoomService->createMeeting($zoomData);

        
        $data['zoom_meeting_id'] = $zoomMeeting['id'] ?? null;
        $data['join_url'] = $zoomMeeting['join_url'] ?? null;
        //$data['start_url'] = $zoomMeeting['start_url'] ?? null;
        $data['duration'] = $zoomData['duration'];

       
        $meeting = Meeting::create($data);

        if (!empty($users)) {
            $meeting->users()->attach($users);
        }

        return $this->success(
            new MeetingResource($meeting->load('users')),
            'Meeting created successfully',
            201
        );

    } catch (\Exception $e) {
        return $this->error(
            'Failed to create meeting: ' . $e->getMessage(),
            500
        );
    }
}

//  public function store(MeetingRequest $request)
// {
//     $data = $request->validated();

//     $users = $data['users'];
//     unset($data['users']);

//     $meeting = Meeting::create($data);
//     $meeting->users()->attach($users);

//     return $this->success(new MeetingResource($meeting->load('users')),
//         'Meeting created successfully', 201);

// }


public function testZoomToken(ZoomTokenService $zoomTokenService)
{
    try {
        $token = $zoomTokenService->getAccessToken();
        return response()->json([
            'success' => true,
            'token' => $token
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meeting = Meeting::with('users')->findOrFail($id);

        if (!$meeting) {
            return $this->error('Meeting not found', 404, []);
        }
        return $this->success(new MeetingResource($meeting),
            'Meeting retrieved successfully', 200);
    }

    public function comment(CommentMeetingRequest $request)
    {
        $data = $request->validated();

        $comment = CommentMeeting::create($data);

        return $this->success($comment,
            'Comment added successfully', 201);
    }


        public function getComments($meetingId)
    {
        $comments = CommentMeeting::with('user')
                                        ->MeetingComments($meetingId)
                                        ->get();

        return $this->success(CommentMeetingResource::collection($comments),
            'Comment added successfully', 201);
    }

    /**
     * Update the specified resource in storage.
        */

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
