<?php
namespace App\Jobs;

use App\Models\User;
use App\Services\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendFollowUpEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    protected $user;
    protected $day;

    public function __construct(User $user, int $day)
    {
        $this->user = $user;
        $this->day = $day;
    }

    public function handle()
    {
        try {
            $mailer = config('mail.default');
            $body = "Hi {$this->user->name}, this is your day {$this->day} follow-up email.";
            Mail::mailer($mailer)->raw($body, function ($message) {
                $message->to($this->user->email)
                    ->subject("Follow-up Day {$this->day}");
            });

            \Log::info("Follow-up email sent to {$this->user->email} for day {$this->day}.");
        } catch (\Throwable $e) {
            \Log::error("Failed to send day {$this->day} email to {$this->user->email}: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        \Log::critical("Follow-up email job failed permanently for {$this->user->email}: " . $exception->getMessage());
    }
}
