<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SemaphoreService
{
    /**
     * @return array<string, mixed>|false
     */
    public function send(string $number, string $message): array|false
    {
        try {
            $response = Http::post('https://api.semaphore.co/api/v4/messages', [
                'apikey' => config('services.semaphore.api_key'),
                'number' => $number,
                'message' => $message,
                'sendername' => config('services.semaphore.sender_name'),
            ]);
        } catch (RequestException $e) {
            Log::error('Semaphore SMS request failed', ['error' => $e->getMessage()]);

            return false;
        }

        if ($response->failed()) {
            Log::error('Semaphore SMS Failed', ['status' => $response->status()]);

            return false;
        }

        return $response->json();
    }
}
