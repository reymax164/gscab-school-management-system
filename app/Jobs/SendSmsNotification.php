<?php

namespace App\Jobs;

use App\Services\SemaphoreService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSmsNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $number,
        public string $message,
    ) {}

    public function handle(SemaphoreService $semaphore): void
    {
        // automatically injects the SemaphoreService
        $semaphore->send($this->number, $this->message);
    }
}
