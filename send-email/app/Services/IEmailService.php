<?php

namespace App\Services;
use Illuminate\Mail\Mailable;

use App\DTO\EmailSentDTO;

interface IEmailService
{
    public function sendEmail(Mailable $mailable): EmailSentDTO;
}
