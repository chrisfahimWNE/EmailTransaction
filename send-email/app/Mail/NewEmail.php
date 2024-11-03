<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $htmlContent;
    public string $token;
    public string $type;
    public string $version;

    /**
     * Create a new message instance.
     */
    public function __construct(array $validated)
    {
        $this->from = $validated['from']; // Assuming a single sender
        $this->to = $validated['to'];
        $this->subject = $validated['subject'];
        $this->view = $validated['view'];
        $this->viewData = $validated['viewData'];
        $this->type = $this->viewData['type'];
        $this->version = $this->viewData['version'];
        $this->token = $this->viewData['token'];
        $this->htmlContent = $validated['renderedContent'];
    }


    
}
