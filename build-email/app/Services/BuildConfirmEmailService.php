<?php

namespace App\Services;

use App\Services\BuildEmailService;
use Illuminate\Mail\Mailable;

use App\Mail\ConfirmEmail;
use App\DTO\BuildEmailDTO;

class BuildConfirmEmailService extends BuildEmailService
{
    public function buildEmail(BuildEmailDTO $buildEmailDTO): Mailable
    {
        $jwt = $this->jwtService->generateEmailToken($buildEmailDTO);
        $buildEmailDTO->token = $jwt;

        $buildEmailDTO->type = "confirm";

        $confirmMailable = new ConfirmEmail( $jwt, $buildEmailDTO->version);
        $confirmMailable->subject = "Confirm Email";
        $confirmMailable->to($buildEmailDTO->email, $buildEmailDTO->name);
        $confirmMailable->from(env('MAIL_FROM_ADDRESS'), "Chris Fahim");
        return $confirmMailable;
    }
}
