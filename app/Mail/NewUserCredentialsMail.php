<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class NewUserCredentialsMail extends Mailable
{
    public function __construct(
        public string $username,
        public string $temporaryPassword,
        public string $recipientName = 'Team member',
        public ?string $loginUrl = null,
    ) {
        $this->loginUrl ??= rtrim(config('frontend.url'), '/') . '/login';
    }

    public function build(): self
    {
        return $this->subject('Your account is ready — sign-in details')
            ->view('emails.new-user-credentials');
    }
}
