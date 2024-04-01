<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketArchivo extends Model
{
    use HasFactory;
    protected $table = 'ticket_archivos';
    protected $primaryKey = 'if_ticket_archivo';
    public $timestamps = false;

    protected $fillable = [
        'if_ticket_archivo',
        'id_ticket',
        'archivo'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket');
    }
}
