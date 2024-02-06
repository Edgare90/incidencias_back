<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Perfiles;

class UsuarioController extends Controller
{
    public function verificarUsuarioExistente(Request $request)
    {
        $usuario = $request->input('usuario');
        $existe = Usuario::where('usuario', $usuario)->exists();
        return response()->json(['existe' => $existe]);
    }
    
    public function getPerfiles()
    {
        $registros = Perfiles::all()->where('estatus', 'ACT');
        return response()->json($registros);
    }
}
