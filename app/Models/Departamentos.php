<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamentos extends Model
{
    use HasFactory;
    protected $table = 'departamentos';
    protected $primaryKey = 'id_departamento';
    public $timestamps = false;

    protected $fillable = [
        'id_departamento',
        'departamento',
        'estatus'
    ];


    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
