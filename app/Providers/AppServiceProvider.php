<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {

            if (Schema::hasTable('settings')) {

                $setting = Setting::first();

                View::share('setting', $setting);

            }

            if (app()->environment('production')) {
                
            URL::forceScheme('https');
            
            }

        } catch (\Exception $e) {

            View::share('setting', null);

        }
    }
}