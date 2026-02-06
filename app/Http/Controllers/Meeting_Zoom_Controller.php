<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Services\ZoomService; // افترضنا إننا عملنا الخدمة دي
use App\Services\ZoomServices;
use Illuminate\Http\Request;
use Carbon\Carbon;

class Meeting_Zoom_Controller extends Controller
{
    protected $zoomService;

    public function __construct(ZoomServices $zoomService)
    {
        $this->zoomService = $zoomService;
    }

    public function store(Request $request)
    {
       
        // 1. الفلترة والتأكد من البيانات
        $validated = $request->validate([
            'subject'    => 'required|string|max:255',
            'note'       => 'nullable|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required',

        ]);

        // 2. تحويل الوقت لصيغة زووم (ISO8601)
        // بندمج التاريخ مع وقت البدء
        $fullStartTime = Carbon::parse($request->date . ' ' . $request->start_time);
        $duration = $fullStartTime->diffInMinutes(Carbon::parse($request->date . ' ' . $request->end_time));

        // 3. إرسال الطلب لزووم
        $zoomResponse = $this->zoomService->createMeeting([
            'topic'      => $validated['subject'],
            'start_time' => $fullStartTime->format('Y-m-d\TH:i:s'),
            'duration'   => $duration,
        ]);

        // 4. تخزين البيانات في جدولك
        $meeting = Meeting::create([
            'subject'         => $validated['subject'],
            'note'            => $validated['note'],
            'date'            => $validated['date'],
            'start_time'      => $validated['start_time'],
            'end_time'        => $validated['end_time'],
            'zoom_meeting_id' => $zoomResponse['id'],
            'start_url'       => $zoomResponse['start_url'],
            'join_url'        => $zoomResponse['join_url'],
            'zoom_password'   => $zoomResponse['password'] ?? null,
        ]);
$meeting->users()->attach([1,2,3]);
        return response()->json([
            'message' => 'تم إنشاء الميتينج وربطه بزووم بنجاح',
            'data'    => $meeting
        ], 201);
    }
}
