<?php

namespace App\Providers;

use App\Services\JWTService;
use Illuminate\Support\ServiceProvider;
use App\Services\BuildEmailService;
use App\Services\BuildConfirmEmailService;

class EmailBuilderServiceProvider extends ServiceProvider {
    public function register() {
        // Binding the service to the container
        $this->app->bind(BuildEmailService::class, function ($app) {
            // Retrieve the request parameter
            $request = app('request');
            $serviceType = $request->get('type'); // e.g., ?service=first or ?service=second

            //used to inject into build email services
            $jwtServcice = $app->make(JWTService::class);

            // Return the appropriate service implementation
            switch ($serviceType) {
                case 'confirm-email':
                    return new BuildConfirmEmailService($jwtServcice);
                default:
                    throw new \InvalidArgumentException("Invalid service type");
            }
        });
    }
}