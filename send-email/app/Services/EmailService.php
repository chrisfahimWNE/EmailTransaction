<?php

namespace App\Services;

use App\Mail\SendConfirmEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

use App\DTO\ConfirmEmailDTO;

class EmailService
{
    protected $jwtService;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    

    public function getConfirmEmail(ConfirmEmailDTO $confirmEmailDTO)
    {
        $jwt = $this->jwtService->generateConfirmToken($confirmEmailDTO);
        $confirmMailable = new SendConfirmEmail( $jwt);
        $confirmMailable->subject = "Confirm Email";
        $confirmMailable->to($confirmEmailDTO->email, $confirmEmailDTO->name);
        $confirmMailable->from(env('MAIL_FROM_ADDRESS'), "Chris Fahim");
        return $confirmMailable;
    }
}
