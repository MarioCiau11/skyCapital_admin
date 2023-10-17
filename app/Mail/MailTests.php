<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailTests extends Mailable
{
    use Queueable, SerializesModels;
    protected $usuario,$contraseña,$files = array();

    /**
     * Create a new message instance.
     */
    public function __construct($usuario,$contraseña,$files = array())
    {
        $this->usuario = $usuario;
        $this->contraseña = $contraseña;
        $this->files = $files;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Mail Tests',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.AccesosClientes',
            with:[
                'usuario' => $this->usuario,
                'contraseña' => $this->contraseña,
                'images' => $this->files    
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
