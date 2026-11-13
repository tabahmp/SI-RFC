<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AkunDiaktifkan extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Akun SI-RFC Anda Telah Diaktifkan');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.akun-diaktifkan', with: [
            'nama'     => $this->user->nama_lengkap ?: $this->user->name,
            'urlLogin' => url('/login'),
        ]);
    }
}
