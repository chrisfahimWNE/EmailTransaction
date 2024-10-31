<?php

namespace App\Services;

use App\DTO\EmailSentDTO;


use Illuminate\Mail\Mailable;


use App\Services\IEmailService;
use Nette\NotImplementedException;


class PostMarkMailService implements IEmailService
{
    protected $postMark;

    public function __construct()
    {
        
    }

    public function sendEmail(Mailable $mailable): EmailSentDTO
    {
         throw new NotImplementedException();
    }
}

