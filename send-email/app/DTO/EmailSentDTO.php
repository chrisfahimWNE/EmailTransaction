<?php

namespace App\DTO;

class EmailSentDTO
{
    public string $status;
    public array $body;
    public array $headers;
    public string $emailId;
    
    public function __construct(string $status, array $body, array $headers, string $email_id)
    {
        $this->status = $status;
        $this->body = $body;
        $this->headers = $headers;
        $this->emailId = $email_id;
    }
}
