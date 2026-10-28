<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class AccountRecoveryMail extends Mailable
{
    public function __construct(
        public string $kind,
        public string $recipientName,
        public ?string $username = null,
        public ?string $otp = null,
    ) {}

    public function build(): self
    {
        return $this->subject($this->kind === 'otp' ? 'Your password reset verification code' : 'Your account username')
            ->view('emails.account-recovery')
            ->with(['loginUrl' => rtrim(config('frontend.url'), '/') . '/login']);
    }
}
