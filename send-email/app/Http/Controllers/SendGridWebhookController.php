<?php

namespace App\Http\Controllers;

use App\Services\EmailService;
use App\Services\JWTService;
use App\Services\PulsarService;



class SendGridWebhookController extends Controller
{
    public function __construct(EmailService $emailService, JWTService $jWTService,
                                PulsarService $pulsarService)
    {
        $this->emailService = $emailService;
        $this->jwtService = $jWTService;
        $this->pulsarService = $pulsarService;
    }
}
