<?php

namespace App\Services;

use App\Services\ZoomTokenService;
use Illuminate\Support\Facades\Http;

class ZoomMeetingService
{
    protected ZoomTokenService $tokenService;

    public function __construct(ZoomTokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    
 public function createMeeting(array $data)
{
    $token = $this->tokenService->getAccessToken();

    $response = Http::withToken($token)
        ->post("https://api.zoom.us/v2/users/me/meetings", [
            'topic' => $data['topic'],
            'type' => $data['type'] ?? 2,
            'start_time' => $data['start_time'],
            'duration' => $data['duration'] ?? 30,
            'timezone' => $data['timezone'] ?? 'Africa/Cairo',
            'agenda' => $data['agenda'] ?? '',
        ]);

    if (!$response->successful()) {
        throw new \Exception($response->body());
    }

    return $response->json();
}

}
