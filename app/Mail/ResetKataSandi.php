<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetKataSandi extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $token,
        public readonly string $email,
        public readonly string $nama = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Reset Kata Sandi — SI-RFC');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.reset-kata-sandi', with: [
            'token'      => $this->token,
            'email'      => $this->email,
            'nama'       => $this->nama,
            'urlReset'   => url("/reset-kata-sandi/{$this->token}?email=" . urlencode($this->email)),
        ]);
    }
}
