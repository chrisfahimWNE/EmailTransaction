<?php

namespace App\Services;

use App\DTO\EmailSentDTO;
use SendGrid;
use SendGrid\Mail\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail as LaravelMail;

use App\Services\IEmailService;


class SendGridMailService implements IEmailService
{
    protected SendGrid $sendGrid;

    public function __construct()
    {
        $this->sendGrid = new SendGrid(env('MAIL_PASSWORD'),
            ['verify_ssl' => false], // Disable SSL verification
        );
    }

    public function sendEmail(Mailable $mailable): EmailSentDTO
    {
        // Render the Mailable to HTML
        //$htmlContent = LaravelMail::to($mailable->to)
          //  ->send($mailable)
            //->render();

        $email = new Mail();
        $email->setFrom(env('MAIL_FROM_ADDRESS'), "Chris Fahim");
        $email->setSubject($mailable->subject);
        //$email->addTos($mailable->to);
        // Ensure $mailable->to is an array of email addresses
        var_dump("mailable.to is: ",$mailable->to);
        foreach ($mailable->to as $recipient) {
            $email->addTo($recipient['address'], $recipient['name']);
        }
        $email->addContent("text/html", $mailable->render());

        try {
            
            $response = $this->sendGrid->send($email);
            
            var_dump($response);
            return new EmailSentDTO($response->statusCode(),
                [],
                $response->headers(true),
                $response->headers(true)['X-Message-Id']
            );
     
        } catch (\Exception $e) {
            // Handle error
            //return ['error' => $e->getMessage()];
            var_dump($e);
        }
        return new EmailSentDTO("500", [], [], "err");
    }
}

