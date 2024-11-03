<?php

namespace App\Providers;

use App\Services\IEmailService;
use App\Services\SendGridMailService;
use Illuminate\Support\ServiceProvider;
use Nette\NotImplementedException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $mailService = env("MAIL_SERVICE_PROVIDER");
        
        if  ($mailService === "sendgrid") {
            $this->app->bind(IEmailService::class, SendGridMailService::class);
        }
        elseif ($mailService === "postmark") {
            //load the postmark service
            throw new NotImplementedException("postmark mail service not implemented yet");
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
