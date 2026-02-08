<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ZoomServices
{
    protected $baseUrl = 'https://api.zoom.us/v2/';

    // وظيفة للحصول على الـ Access Token أوتوماتيكياً
    protected function getToken()
    {
        $response = Http::asForm()->withBasicAuth(
            config('services.zoom.client_id'),
            config('services.zoom.client_secret')
        )->post("https://zoom.us/oauth/token?grant_type=account_credentials&account_id=" . config('services.zoom.account_id'));
if ($response->failed()) {
        return dd($response->json());
    }
        return $response->json()['access_token'];
    }

    // وظيفة إنشاء اجتماع (Meeting)
    public function createMeeting($data)
    {
        $token = $this->getToken();

        $response = Http::withToken($token)
            ->post($this->baseUrl . "users/me/meetings", [
                'topic'      => $data['topic'],
                'type'       => 2, // Scheduled Meeting
                'start_time' => $data['start_time'], // Format: 2026-02-06T10:00:00Z
                'duration'   => $data['duration'],
                'settings'   => [
                    'host_video' => true,
                    'participant_video' => true,
                    'join_before_host' => false,
                    'mute_upon_entry' => true,
                ]
            ]);

        return $response->json();
    }
}
