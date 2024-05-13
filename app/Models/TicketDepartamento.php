<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDepartamento extends Model
{
    use HasFactory;
    protected $table = 'ticket_departamentos';
    protected $primaryKey = 'id_ticket_departamento';
    public $timestamps = false;

    protected $fillable = [
        'id_ticket_departamento',
        'id_ticket',
        'id_departamento'
    ];

}
