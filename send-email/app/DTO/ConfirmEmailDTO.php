<?php

namespace App\DTO;

class ConfirmEmailDTO
{
    public string $name;
    public string $email;
    public ?string $metaData;

    public function __construct(string $name, string $email, ?string $metaData = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->metaData = $metaData;
    }
}
