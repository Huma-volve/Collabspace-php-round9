<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ZoomTokenService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $accountId;

    public function __construct()
    {
        $this->clientId = config('services.zoom.client_id');
        $this->clientSecret = config('services.zoom.client_secret');
        $this->accountId = config('services.zoom.account_id');
    }

    public function getAccessToken(): string
    {
        return Cache::remember('zoom_access_token', 3500, function () {

            $response = Http::withBasicAuth(
                    $this->clientId,
                    $this->clientSecret
                )
                ->asForm()
                ->post('https://zoom.us/oauth/token', [
                    'grant_type' => 'account_credentials',
                    'account_id' => $this->accountId,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Zoom token error: ' . $response->body());
            }

            return $response->json()['access_token'];
        });
    }
}
