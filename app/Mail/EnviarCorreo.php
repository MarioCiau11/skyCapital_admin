<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\config\CONF_PARAMETROS_GENERALES;
use App\Models\catalogos\CAT_EMPRESAS;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class EnviarCorreo extends Mailable
{
    use Queueable, SerializesModels;
    protected $cliente,$contraseña,$urlPortal,$empresa;
    protected $files = [];

    /**
     * Create a new message instance.
     */
    public function __construct($cliente, $contraseña,$empresa,$urlPortal)
    {
        $this->cliente = $cliente;
        $this->contraseña = $contraseña;
        $this->urlPortal = $urlPortal;
        $this->empresa = $empresa;
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenido a Empresa '.$this->empresa.' - Datos de acceso al portal de clientes',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {   

        // dd($this->urlPortal);
        $this->files = [
            'Laptop' => public_path('images\mail\Laptop.jpg'),
            'LogotipoSkyBlanco' => public_path('images\mail\LogotipoSkyBlanco.png'),
            'LogotipoSkyNegro' => public_path('images\mail\LogotipoSkyNegro.png'),
            'OpcionesPortal' => public_path('images\mail\OpcionesPortal.png')
        ];
        return new Content(
            view: 'mail.AccesosClientes',
            with:[
                'usuario' => $this->cliente,
                'contraseña' => $this->contraseña,
                'images' => $this->files,
                'portal' => $this->urlPortal.'/login'   
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
