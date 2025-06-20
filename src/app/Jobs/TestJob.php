<?php

namespace App\Jobs;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    /**
     * Execute the job.
     *
     * @return void
     */

        //Todo retry after 5 min and every next add on 5min
        //Todo check threshold value if reach then notify job is failed
        public $tries = 3;
        public $retryAfter = 120; // 5 minutes in seconds

        public function handle()
    {
        $key = 'job:retry:user:10';

        try {
            $user = User::find(10);
            if (!$user) {
                throw new \Exception("User not found");
            }

            \Log::info($user->name);

            // Reset retry count on success (optional)
            Redis::del($key);

        } catch (\Throwable $e) {
            $attempts = Redis::incr($key);

            if ($attempts <= $this->tries) {
                \Log::info("Retrying attempt $attempts for user 10");
                $this->release($this->retryAfter); // delay next retry
            } else {
                \Log::error("Job failed after max retries");
                // Optionally notify failure via email, Slack, etc.
            }
        }
    }

}
