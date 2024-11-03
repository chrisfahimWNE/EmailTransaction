<?php

namespace App\DTO;

class BuildEmailDTO
{
    public string $email;
    public string $name;
    public string $version;
    public ?string $token;
    public ?string $type;
    public ?string $metaData;
    
    
    public function __construct(array $validated)
    {
        $this->email = $validated["email"];
        $this->name = $validated["name"];
        $this->version = $validated["version"];
        $this->token = $validated["token"] ?? null; //this will populate after email build
        $this->type = $validated["type"] ?? null; //this will populate during email build
        $this->metaData = $validated["metadata"] ?? null;
    }

}
