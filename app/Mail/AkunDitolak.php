<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AkunDitolak extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $catatan = ''
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Pemberitahuan Pendaftaran Akun SI-RFC');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.akun-ditolak', with: [
            'nama'    => $this->user->nama_lengkap ?: $this->user->name,
            'catatan' => $this->catatan,
        ]);
    }
}
