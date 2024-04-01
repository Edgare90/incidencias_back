<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketEstatus extends Model
{
    use HasFactory;

    protected $table = 'ticket_estatus';
    protected $primaryKey = 'id_ticket_estatus';
    public $timestamps = false;

    protected $fillable = [
        'id_ticket_estatus',
        'id_ticket',
        'id_estatus',
        'usr',
        'fecha'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket');
    }

    public function estatusInfo()
    {
        return $this->belongsTo(Estatus::class, 'id_estatus', 'id_estatus');
    }
}
