<?php

namespace App\Http\Controllers;

use App\Services\IEmailService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;



class EmailWebhookController extends Controller
{
    protected IEmailService $emailService;

    public function __construct(IEmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    
    public function emailWebhook(Request $request){
        $this->emailService->handleWebhook($request);
    }
}
