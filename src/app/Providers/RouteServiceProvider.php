<?php

namespace App\Providers;

use App\Helpers\RedisHelper;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('plan_based', function (Request $request) {
            $plan = $request->input('plan', 'guest'); // or use $request->user()?->plan
            $limit = match ($plan) {
                'premium' => 2,
                'free' => 1,
                default => 1,
            };

            $key = RedisHelper::buildKey('rate_limit', $plan, $request->ip());

            $count = Redis::incr($key);
            if ($count === 1) {
                Redis::expire($key, 120);
            }
            return Limit::perMinute($limit)->by($key);
        });
    }
}
