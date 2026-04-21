<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Resource;
use App\Policies\ResourcePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Resource::class, ResourcePolicy::class);

        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}