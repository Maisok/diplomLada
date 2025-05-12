<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmailChange extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $user;

    public function __construct($token, $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Подтверждение изменения email')
            ->markdown('emails.verify-email-change-html')
            ->with([
                'verificationUrl' => route('profile.verify-email', $this->token),
                'oldEmail' => $this->user->email,
            ]);
    }
}