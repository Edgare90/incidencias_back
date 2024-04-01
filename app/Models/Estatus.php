<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;
    protected $table = 'estatus';
    protected $primaryKey = 'id_estatus';
    public $timestamps = false;

    protected $fillable = [
        'id_estatus',
        'estatus_desc'
    ];
}
