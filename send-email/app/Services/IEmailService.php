<?php

namespace App\Services;

use App\Mail\NewEmail;
use App\DTO\EmailSentDTO;

use Illuminate\Http\Request;

interface IEmailService
{
    public function sendEmail(NewEmail $mailable): EmailSentDTO;
    public function handleWebhook(Request $request): void;
}
