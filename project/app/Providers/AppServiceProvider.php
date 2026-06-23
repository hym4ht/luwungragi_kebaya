<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $locale = config('app.locale');

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        if ($locale === 'id') {
            setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'id');
        }

        if (request()->server('HTTP_X_FORWARDED_PROTO') === 'https' || str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }
    }
}
