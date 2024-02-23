<?php

namespace App\Http\Controllers;
use App\Models\Perfiles;

use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function getPerfiles()
    {
        $registros = Perfiles::all();
        return response()->json($registros);
    }


    public function editaPerfil(Request $request)
    {
        $idPerfil = $request->input('idPerfil');

        $perfil = Perfiles::find($idPerfil);

        if (!$perfil) {
            return response()->json(['error' => 'Perfil no encontrado'], 404);
        }

        if($perfil->estatus == 'ACT')
        {
            $perfil -> estatus = 'IN';
        }else{
            $perfil -> estatus = 'ACT';
        }

        if($perfil->save())
        {
            return response()->json(['mensaje' => 'Perfil actualizado con éxito']);
        }else {
            return response()->json(['mensaje' => 'Error al Actualizar Perfil']);
        }
    }
}
