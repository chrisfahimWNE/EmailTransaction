<?php

namespace App\DTO;

class EmailSentDTO
{
    public string $status;
    public array $body;
    public array $headers;
    public string $email_id;
    
    public function __construct(string $status, array $body, array $headers, string $email_id)
    {
        $this->status = $status;
        $this->body = $body;
        $this->headers = $headers;
        $this->email_id = $email_id;
    }
}
