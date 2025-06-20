<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

/**
 * @property User $user
 */
class SyncUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;

    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            \Log::info("User syncing started",['email' => $this->user->email]);
            $nameParts = $this->user->name ? explode(' ', $this->user->name, 2) : ['Unknown', ''];
            $response = Http::post(config('services.sync_service.sync_url'), [
                'email'     => $this->user->email,
                'firstName' => $nameParts[0],
                'lastName'  => $nameParts[1] ?? '',
            ]);

            if ($response->successful()) {
                \Log::info('User sync successful', [
                    'email' => $this->user->email,
                    'response' => $response->body(),
                ]);
                return true;
            }

            \Log::warning('User sync failed', [
                'email' => $this->user->email,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

        } catch (\Throwable $e) {
            \Log::error('Exception during user sync', [
                'email' => $this->user->email,
                'message' => $e->getMessage(),
            ]);
        }

        return false;
    }
}
