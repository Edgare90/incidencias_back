<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfiles extends Model
{
    use HasFactory;
    protected $table = 'perfiles';
    protected $primaryKey = 'id_perfil';
    public $timestamps = false;

    protected $fillable = [
        'id_perfil',
        'perfil',
        'estatus'
    ];
}
