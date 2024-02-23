<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_usuario',
        'usuario',
        'email',
        'perfil',
        'estatus'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($usuario) {
            $usuario->password = Hash::make($usuario->password);
        });

        static::updating(function ($usuario) {
            if ($usuario->isDirty('password')) {
                $usuario->password = Hash::make($usuario->password);
            }
        });
    }

    public function Departamento()
    {
        return $this->hasOne(Departamentos::class);
    }
}
