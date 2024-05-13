<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $table = 'ticktes';
    protected $primaryKey = 'id_ticket';
    public $timestamps = false;

    protected $fillable = [
        'id_ticket',
        'usr_alta',
        'fecha_alta',
        'ticket_anterior'
    ];

    public function archivos()
    {
        return $this->hasMany(TicketArchivo::class, 'id_ticket');
    }

    public function comentarios()
    {
        return $this->hasMany(TicketComentario::class, 'id_ticket');
    }

    public function estatus()
    {
        return $this->hasMany(TicketEstatus::class, 'id_ticket');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usr_alta', 'id_usuario');
    }

    public function departamentos()
    {
        return $this->belongsToMany(Departamentos::class, 'ticket_departamentos', 'id_ticket', 'id_departamento');
    }

}
