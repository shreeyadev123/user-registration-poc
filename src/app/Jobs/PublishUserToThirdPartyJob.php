<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PublishUserToThirdPartyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        try {
            $response = Http::post(config('services.webhook.site_url'), [
                'email'     => $this->user->email,
                'firstName' => $this->user->first_name,
                'lastName'  => $this->user->last_name,
            ]);

            if ($response->successful()) {
                Log::info("✅ User published to 3rd party: {$this->user->email}");
                return;
            }
            throw new \Exception("Non-2xx response: {$response->status()}");

        } catch (\Throwable $e) {
            $attempt = $this->attempts(); // includes current attempt

            Log::warning("Attempt {$attempt}: Failed to publish user {$this->user->email} - {$e->getMessage()}");

            if ($attempt < 3) {
                $delays = [2, 10, 15]; // in minutes
                $delay = $delays[$attempt - 1] ?? 15;
                $this->release($delay * 60); // delay in seconds
            } else {
                Log::error("FINAL FAIL: User publish failed after 3 attempts for {$this->user->email}");
            }
        }
    }

}
