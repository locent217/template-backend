<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class PasswordChangedMail extends Mailable
{
    public function __construct(public readonly string $recipientName, public readonly string $method) {}

    public function build(): self
    {
        return $this->subject('Security alert: your password was changed')
            ->view('emails.password-changed')
            ->with(['loginUrl' => rtrim(config('frontend.url'), '/') . '/login']);
    }
}
