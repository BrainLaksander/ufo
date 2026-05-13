<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;
    public string $orgName;

    public function __construct(string $resetUrl, string $orgName)
    {
        $this->resetUrl = $resetUrl;
        $this->orgName = $orgName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password Akun - UFO',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.password-reset',
        );
    }
}
