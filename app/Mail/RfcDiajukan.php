<?php

namespace App\Mail;

use App\Models\PermohonanRfc;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RfcDiajukan extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly PermohonanRfc $rfc,
        public readonly ?User $penerima = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[RFC Baru] {$this->rfc->kode_pelacakan} — Memerlukan Telaah"
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.rfc-diajukan', with: [
            'rfc'          => $this->rfc,
            'namaPenerima' => $this->penerima?->nama_lengkap ?? 'Verifikator',
            'urlWorkspace' => url("/rfc/{$this->rfc->kode_pelacakan}/workspace"),
        ]);
    }
}
