<?php

namespace App\Http\Controllers;

use App\DTO\ConfirmEmailDTO;
use App\Services\EmailService;
use App\Services\SendGridMailService;
use Illuminate\Http\Request;
use App\Services\JWTService;
use App\Services\PulsarService;

use App\Models\OtJWT;

class EmailController extends Controller
{
    protected $emailService;
    protected $jwtService;
    protected $pulsarService;
    protected $emailBuilder;

    public function __construct(SendGridMailService $emailService, JWTService $jWTService,
                                PulsarService $pulsarService, EmailService $emailBuilder)
    {
        $this->emailService = $emailService;
        $this->jwtService = $jWTService;
        $this->pulsarService = $pulsarService;
        $this->emailBuilder = $emailBuilder;
    }

    public function receiveConfirm(Request $request)
    {
        $token = $request->query("token");
        $otJWT = OtJWT::where("token", $token)->first();
        if (!$otJWT || !$otJWT->active) {
            return response()->json([
                "status"=> "error",
                "message"=> "expired or invalid token"
                ],404);
        }

        $decodedJWT = $this->jwtService->decodeToken($token);
        if (!$decodedJWT) {
            return response()->json([
                "status"=> "error",
                "message"=> "expired or invalid token"
                ],404);
        }

        $otJWT->active = false;
        $otJWT->save();

        $message = [
            "token"=> $otJWT->token,
            "email"=> $otJWT->email,
            "active" => $otJWT->active
        ];
        $this->pulsarService->produceMessage("persistent://public/default/received-confirm-email", $message);

        return response()->json([
            'message' => 'Redirecting...',
            'redirect' => url('/'),
        ], 200);


    }

    public function sendConfirmEmail(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'metaData' => 'nullable|string',
        ]);

        $confirmEmailDTO = new ConfirmEmailDTO(
            $validated['name'],
            $validated['email'],
            $validated['metaData'] ?? null
        );

        $emailbuild = $this->emailBuilder->getConfirmEmail($confirmEmailDTO);        
        $email = $this->emailService->sendEmail($emailbuild);
        
        var_dump($email);


        return response()->json(['success' => 'Confirm Email sent successfully!']);
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);

        $recipient = $request->input('recipient');
        $subject = $request->input('subject');
        $message = $request->input('message');

        $this->emailService->sendEmail($recipient, $subject, $message);

        return response()->json(['success' => 'Email sent successfully!']);
    }
}
