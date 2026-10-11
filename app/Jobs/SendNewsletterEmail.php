<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(
        public string $email,
        public string $subject,
        public string $body,
    ) {}

    public function handle(): void
    {
        try {
            Mail::raw($this->body, function ($m) {
                $m->to($this->email)->subject($this->subject);
            });
        } catch (\Throwable $e) {
            Log::warning('[job] newsletter mail failed', [
                'email' => $this->email,
                'err'   => $e->getMessage(),
            ]);
            throw $e; // позволит Laravel попробовать снова (tries=3)
        }
    }
}