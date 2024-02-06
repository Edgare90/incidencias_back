<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'users'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'id_usuario',
        'usuario',
        'email',
        'perfil',
        'estatus'
    ];
}
