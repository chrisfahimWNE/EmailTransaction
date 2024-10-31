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

    public function sendEmail($recipient, $subject, $message)
    {
        Mail::to($recipient)->send(new SendEmail($subject, $message));
    }

    public function sendConfirmEmail(ConfirmEmailDTO $confirmEmailDTO, $token)
    {
        Mail::to($confirmEmailDTO->email)->send(new SendConfirmEmail("Confirm Email", $token));
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
