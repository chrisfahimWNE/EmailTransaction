<?php

namespace App\Services;

use App\DTO\BuildEmailDTO;
use Illuminate\Mail\Mailable;

abstract class BuildEmailService
{
    protected $jwtService;
    protected $from;

    public function __construct(JwtService $jwtService)
    {
        $this->jwtService = $jwtService;
        $this->from = env('MAIL_FROM_ADDRESS');
    }

    /**
     * Quick function to minify html content for the emails
     * @param string $html
     * @return string
     */
    public static function minifyHtml(string $html) {
        // Remove comments
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        
        // Remove extra whitespace
        $html = preg_replace('/\s+/', ' ', $html);
        
        // Remove space between tags
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Trim the result
        return trim($html);
    }

    /**
     * Builds the email and renders the html into renderedContent property
     * @param \App\DTO\BuildEmailDTO $buildEmailDTO
     * @return Mailable
     */
    public function renderMailable(BuildEmailDTO $buildEmailDTO){
        $mailable = $this->buildEmail($buildEmailDTO);
        $mailable->renderedContent = BuildEmailService::minifyHtml($mailable->render());
        return $mailable;
    }

    abstract public function buildEmail(BuildEmailDTO $buildEmailDTO): Mailable;
}
