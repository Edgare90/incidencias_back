<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketComentario extends Model
{
    use HasFactory;
    protected $table = 'ticket_comentario';
    protected $primaryKey = 'id_ticket_comentario';
    public $timestamps = false;

    protected $fillable = [
        'id_ticket_comentario',
        'id_ticket',
        'usr',
        'fecha_comentario',
        'comentario'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket');
    }
}
