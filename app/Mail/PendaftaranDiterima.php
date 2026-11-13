<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranDiterima extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public readonly User $user) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Pendaftaran Akun SI-RFC Berhasil Diterima');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.pendaftaran-diterima', with: [
            'nama'  => $this->user->nama_lengkap ?: $this->user->name,
            'email' => $this->user->email,
            'opd'   => $this->user->opd?->nama_opd ?? $this->user->asal_opd ?? '-',
        ]);
    }
}
