<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Model
{
    use HasFactory, Notifiable,HasApiTokens ;
    protected $table = 'users';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'id_usuario',
        'usuario',
        'email',
        'perfil',
        'estatus',
        'password',
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
        //return $this->hasOne(Departamentos::class, 'id_departamento');
        return $this->belongsTo(Departamentos::class, 'id_departamento');
    }

    public function comentarios()
    {
        return $this->hasMany(TicketComentario::class, 'usr', 'id_usuario');
    }
}
