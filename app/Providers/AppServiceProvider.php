<?php

namespace App\Providers;

use App\Services\WhatsAppRegistrationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            WhatsAppRegistrationService::class,
            fn (): WhatsAppRegistrationService => new WhatsAppRegistrationService(
                (string) config('services.whatsapp.admin_phone'),
            ),
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
