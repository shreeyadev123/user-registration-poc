<?php

namespace App\Observers;

use App\Jobs\PublishUserToThirdPartyJob;
use App\Jobs\SendFollowUpEmailJob;
use App\Jobs\SendWelcomeEmailJob;
use App\Jobs\SyncUserJob;
use App\Models\User;
use App\Services\UserDataSyncService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        // dispatch welcome email job
        SendWelcomeEmailJob::dispatch($user);
        // dispatch follow up email based on logic , 1 day , 2day and 5 day
        $delays = [1, 2, 5];

        foreach ($delays as $day) {
            SendFollowupEmailJob::dispatch($user, $day)
                ->delay(now()->addDays($day));
        }
        // publish user data to third party
        PublishUserToThirdPartyJob::dispatch($user)->delay(now()->addSeconds(10));
        // sync user data to sync service
        SyncUserJob::dispatch($user)->delay(now()->addSeconds(10));
    }
}
