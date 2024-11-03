<?php

namespace App\Services;

use App\DTO\EmailSentDTO;
use App\Mail\NewEmail;
use App\Services\IEmailService;
use App\Models\EmailAction;
use App\Models\EmailSentLookup;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use SendGrid;
use SendGrid\Mail\Mail;

class SendGridMailService implements IEmailService
{
    protected SendGrid $sendGrid;

    public function __construct()
    {
        $this->sendGrid = new SendGrid(env('MAIL_PASSWORD'),
            ['verify_ssl' => false], // Disable SSL verification
        );
    }

    public function handleWebhook(Request $request): void {
        $validated = $request->validate([
            '*.email' => 'required|email',
            '*.event' => 'required|string',
            '*.ip' => 'required|ip',
            '*.response' => 'required|string',
            '*.sg_event_id' => 'required|string',
            '*.sg_message_id' => 'required|string',
            '*.smtp-id' => 'required|string',
            '*.timestamp' => 'required|integer',
            '*.tls' => 'required|boolean',
        ]);

        // Extracting sg_message_id
        $sgMessageId = $validated[0]['sg_message_id'];

        // Use regex to extract the desired part
        preg_match('/^(.*?)\./', $sgMessageId, $matches);

        // Output the result
        if (isset($matches[1])) {
            $extractedId = $matches[1];
            
            $emailLookup = EmailSentLookup::where('email_id', $extractedId)->first();
            if (!$emailLookup) {
                Log::error("Could not find email token using: {$validated}");
                return;
            }
            
            $emailActionData = [
                'token' => $emailLookup->token,
                'action' => "sendgrid_{$validated[0]['event']}",
                'version' => '',
                'emailType' => '',
            ];
            EmailAction::create($emailActionData);

        }
        else
        {
            Log::error("Could not extract email id from {$validated}");
        }

    }

    public function sendEmail(NewEmail $mailable): EmailSentDTO
    {
        //create the sendgrid mail
        $email = new Mail();
        $email->setFrom($mailable->from[0]["address"], $mailable->from[0]["name"]);
        $email->setSubject($mailable->subject);
        foreach ($mailable->to as $recipient) {
            $email->addTo($recipient['address'], $recipient['name']);
        }
        $email->addContent("text/html", $mailable->htmlContent);

        try {
            
            $response = $this->sendGrid->send($email);
            
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

