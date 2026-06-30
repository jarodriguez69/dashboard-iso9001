<?php

namespace App\Mail;

use App\Models\Auditoria;
use App\Models\Encuesta;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EncuestaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $auditoria;
    public $encuesta;

    public function __construct(Auditoria $auditoria, Encuesta $encuesta)
    {
        $this->auditoria = $auditoria;
        $this->encuesta = $encuesta;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Encuesta de Satisfacción - Auditoría ISO 9001:2015',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.encuesta',
        );
    }
}
