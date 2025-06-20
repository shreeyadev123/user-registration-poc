<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Email\EmailService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        try {
            $mailer = config('mail.default');
            $body = "Hi {$this->user->name}, Thank you for registering!";
            Mail::mailer($mailer)->raw($body, function ($message) {
                $message->to($this->user->email)
                    ->subject("Welcome {$this->user->name} to our App !!");
            });

            \Log::info("Hi {$this->user->name}, Thank you for registering!");
        } catch (\Throwable $e) {
            \Log::error("Failed to send welcome email to {$this->user->email}: " . $e->getMessage());
            throw $e;
        }
    }

    public function failed(\Throwable $exception): void
    {
        $email = $this->user->email ?? '[unknown email]';
        \Log::critical("welcome email sending job failed {$email}: " . $exception->getMessage());
    }
}
