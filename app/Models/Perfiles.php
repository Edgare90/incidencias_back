<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfiles extends Model
{
    use HasFactory;
    protected $table = 'perfiles'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'id_perfil',
        'perfil',
        'estatus'
    ];
}
