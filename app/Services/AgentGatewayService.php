<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Log;

class AgentGatewayService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('simthink.gateway.url');
    }

    public function prepare(array $payload): array
    {

        $url = "{$this->baseUrl}/prepare";
        $response = Http::timeout(120)->post($url, $payload);

        if ($response->failed()) {
            throw new \Exception("Fallo en prepare: " . $response->body());
        }

        Log::info("Response Api Gatewaye - Prepare:", $response->json());

        return $response->json();
    }

    public function run(string $tempId, string $investigationName): array
    {
        $response = Http::timeout(220)->post("{$this->baseUrl}/run", [
            'temp_id' => $tempId,
            'investigation_name' => $investigationName,
        ]);

        if ($response->failed()) {
            throw new \Exception("Fallo en run: " . $response->body());
        }

        Log::info("Response Api Gatewaye - Run:", $response->json());

        return $response->json();
    }
}