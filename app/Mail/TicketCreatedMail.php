<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Ticket;
use App\Models\Usuario;

class TicketCreatedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $ticket;
    public $usuario;
    public $subjectLine;
    

    public function __construct(Ticket $ticket, Usuario $usuario, $action = 'created')
    {
        $this->ticket = $ticket;
        $this->user = $usuario;
        $this->subjectLine = $action == 'created' ? 'Nuevo Ticket Creado' : 'Cambio en Ticket';
    }

    public function build()
    {
        return $this->from('example@example.com')
                    ->subject($this->subjectLine) 
                    ->view('emails.ticketCreated')
                    ->with([
                        'ticketId' => $this->ticket->id_ticket,
                        'fechaAlta' => $this->ticket->fecha_alta,
                        'creadoPor' => $this->user->usuario,
                        'subjectLine' => $this->subjectLine 
                    ]);
    }

    
}
