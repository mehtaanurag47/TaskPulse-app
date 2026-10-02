<?php

namespace App\Services;

use Google\Auth\Credentials\ServiceAccountCredentials;
use Illuminate\Support\Facades\Http;

class FirebaseService
{
    private string $projectId;
    private string $credentialsPath;

    public function __construct()
    {
        $this->projectId = config('firebase.project_id');
        $this->credentialsPath = config('firebase.credentials');
    }

    private function getAccessToken(): string
    {
        $scopes = [
            'https://www.googleapis.com/auth/firebase.messaging'
        ];

        $credentials = new ServiceAccountCredentials(
            $scopes,
            $this->credentialsPath
        );

        $token = $credentials->fetchAuthToken();

        if (!isset($token['access_token'])) {
            throw new \Exception('Unable to generate Firebase Access Token.');
        }

        return $token['access_token'];
    }
    public function sendNotification(
    string $deviceToken,
    string $title,
    string $body
): array {

    $accessToken = $this->getAccessToken();

    $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";

    $payload = [
        "message" => [
            "token" => $deviceToken,

            "data" => [
                "title" => $title,
                "body" => $body,
            ],

            "webpush" => [
                "headers" => [
                    "Urgency" => "high"
                ]
            ]
        ]
    ];

    $response = Http::withToken($accessToken)
        ->acceptJson()
        ->post($url, $payload);

    if (!$response->successful()) {

        throw new \Exception(
            "Firebase Error : " . $response->body()
        );

    }

    return $response->json();
}
}