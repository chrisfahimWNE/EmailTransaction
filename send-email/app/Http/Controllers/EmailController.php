<?php

namespace App\Http\Controllers;

use App\Mail\NewEmail;
use App\Models\EmailSentLookup;
use App\Services\EmailService;
use App\Services\IEmailService;
use App\Services\PulsarService;
use App\Models\EmailAction;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailController extends Controller
{
    protected $emailService;
    protected $pulsarService;
    protected $emailBuilder;

    public function __construct(IEmailService $emailService, PulsarService $pulsarService,
        EmailService $emailBuilder)
    {
        $this->emailService = $emailService;
        $this->pulsarService = $pulsarService;
        $this->emailBuilder = $emailBuilder;
    }

    public function send(Request $request)
    {
    
        try
        {
            // Validate the incoming request data
            $validated = $request->validate([
                'from' => 'required|array',
                'from.*.name' => 'required|string',
                'from.*.address' => 'required|email',
                'to' => 'required|array',
                'to.*.name' => 'required|string',
                'to.*.address' => 'required|email',
                'subject' => 'required|string',
                'view' => 'required|string',
                'viewData.token' => 'required|string',
                'viewData.version' => 'required|string', // Example versions
                'viewData.type' => 'required|string', // Example types
                'renderedContent' => 'required|string',
            ]);
        }
        catch (ValidationException $e) {
             // Handle the exception and return a custom response
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->validator->errors(),
            ], 422);
        }

        $newMailable = new NewEmail($validated);

        //TODO: move to repo/service pattern
        $sentDTO = $this->emailService->sendEmail($newMailable);
        $emailSentLookupData = [
            'token' => $newMailable->token,
            'email_id' => $sentDTO->emailId
        ];
        EmailSentLookup::create($emailSentLookupData);

        //TODO: move to repo/service pattern or as pulsar event push
        $emailActionData = [
            'token' => $newMailable->token,
            'action' => 'email_sent',
            'version' => $newMailable->version,
            'emailType' => $newMailable->type,
        ];
        EmailAction::create($emailActionData);




        return response()->json($sentDTO);
    }
}
