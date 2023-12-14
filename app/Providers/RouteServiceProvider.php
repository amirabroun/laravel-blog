<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')->post('api/telegram/inputs/{token}', function (Request $request, $token) {
                $telegram = $request->all();

                Telegram::sendMessage([
                    'chat_id' => $telegram['message']['from']['id'],
                    'text' => 'در حال دولوپ هستم :)',
                    'reply_to_message_id' => $telegram['message']['message_id'],
                ]);
            });

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api/auth.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api/blog.php'));

            config('blog.can_users_register')
                ? Route::middleware('web')->group(base_path('routes/web/auth.php'))
                : Route::middleware('web')->group(base_path('routes/web/secret-auth.php'));

            Route::middleware('web')
                ->group(base_path('routes/web/blog.php'));
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
    }
}
