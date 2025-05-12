<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationUrl;

    public function __construct($verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }

    public function build()
    {
        return $this->subject('Подтверждение регистрации на BB')
        ->view('emails.custom-verify-email-html')
        ->text('emails.custom-verify-email-text')
        ->with(['url' => $this->verificationUrl]);
        
    }
}