<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Broadcast routes moved to routes/api.php to ensure they use auth:sanctum and correct pathing
        // Broadcast::routes(['middleware' => ['auth:sanctum']]);

        require base_path('routes/channels.php');
    }
}
