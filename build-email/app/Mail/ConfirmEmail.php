<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $token;
    private $version;

    public function __construct($token, $version)
    {
        $this->token = $token;
        $this->version = $version;
    }

    public function build()
    {
        return $this->view("emails.confirm_{$this->version}") // Create a Blade view for the email
                    ->with(['token' => $this->token, 'version'=> $this->version, 'type'=> 'confirm']);
    }
}