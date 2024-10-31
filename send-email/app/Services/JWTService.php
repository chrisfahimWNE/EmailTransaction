<?php

namespace App\Services;

use App\DTO\ConfirmEmailDTO;
use App\Models\OtJWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = env('JWT_SECRET'); // Get your secret key from .env
    }

    public function generateConfirmToken(ConfirmEmailDTO $confirmEmailDTO)
    {
        $payload = [
            'iat' => time(), // Issued at
            'exp' => time() + 60 * 60, // Expiration time (1 hour)
            'sub' => $confirmEmailDTO->email, // Subject (user ID)
            'data' => $confirmEmailDTO
        ];

        $jwt = JWT::encode($payload, $this->secretKey, 'HS256');

        OtJWT::create([
            'email' => $confirmEmailDTO->email,
            'token' => $jwt,
        ]);

        return $jwt;
    }

    public function decodeToken($token)
    {
        try {
            // Decode the JWT
            $decoded = JWT::decode($token, new Key($this->secretKey, 'HS256'));
        
            // Access the custom data
            return $decoded->data;
        } catch (\Exception $e) {
            // Handle token decoding error
            echo "Error decoding JWT: " . $e->getMessage();
        }
    }
}
