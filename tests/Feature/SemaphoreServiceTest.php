<?php

use App\Services\SemaphoreService;
use Illuminate\Support\Facades\Http;

test('semaphore service returns the response payload on success', function () {
    Http::fake([
        'api.semaphore.co/*' => Http::response(['status' => 'success'], 200),
    ]);

    $result = (new SemaphoreService)->send('09171234567', 'Test message');

    expect($result)->toBe(['status' => 'success']);
});

test('semaphore service returns false and logs when the provider request fails', function () {
    Http::fake([
        'api.semaphore.co/*' => Http::response(['error' => 'bad request'], 500),
    ]);

    $result = (new SemaphoreService)->send('09171234567', 'Test message');

    expect($result)->toBeFalse();
});
