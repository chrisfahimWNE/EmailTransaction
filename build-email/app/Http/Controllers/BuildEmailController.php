<?php

namespace App\Http\Controllers;

use App\DTO\BuildEmailDTO;
use App\Models\EmailAction;
use App\Utils\JsonUtils;

use App\Services\BuildEmailService;

use Illuminate\Http\Request;

use App\Services\PulsarService;



class BuildEmailController extends Controller
{
    protected $pulsarService;
    protected $emailBuilder;

    public function __construct(PulsarService $pulsarService, BuildEmailService $emailBuilder)
    {
        $this->pulsarService = $pulsarService;
        $this->emailBuilder = $emailBuilder;
    }

   

    public function buildEmail(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'type' => 'required|string',
            'version' => 'nullable|string',
            'metaData' => 'nullable|string',
        ]);
        

        $buildEmailDTO = new BuildEmailDTO($validated);
        $emailbuild = $this->emailBuilder->renderMailable($buildEmailDTO);

        //produce send message
        //TODO: refactor
        $this->pulsarService->produceMessage("persistent://public/default/send-email", JsonUtils::filterEmptyAndNull($emailbuild));

        //update database
        //TODO: move to repo/service pattern
        $emailActionData = [
            'token' => $buildEmailDTO->token,
            'action' => 'email_built',
            'version' => $buildEmailDTO->version,
            'emailType' => $buildEmailDTO->type,
        ];
        EmailAction::create($emailActionData);


        return response()->json($emailActionData);
    }
}
