<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;
use App\Models\Perfiles;

class UsuarioController extends Controller
{
    public function verificarUsuarioExistente($usuario)
    {
        $usuario = $usuario;
        $existe = Usuario::where('usuario', $usuario)->exists();
        return response()->json(['existe' => $existe]);
    }
    
    public function getPerfiles()
    {
        $registros = Perfiles::all()->where('estatus', 'ACT');
        return response()->json($registros);
    }

    public function getUsuarios()
    {
        $registros = DB::table('users')
        ->join('perfiles', 'users.perfil', '=', 'perfiles.id_perfil')
        ->leftJoin('departamentos', 'users.id_departamento', '=', 'departamentos.id_departamento')
        ->select('users.*', 'perfiles.perfil as nombre_perfil', 'departamentos.departamento as nombre_departamento')
        ->where('users.estatus', 'ACT')
        ->get();
        return response()->json($registros);
    }

    public function GuardaUsuario(Request $request)
    {
        $nuevoRegistro = new Usuario;

        $nuevoRegistro -> usuario = $request->input('usuario');
        $nuevoRegistro ->password  = $request->input('contrasena');
        $nuevoRegistro ->perfil = $request->input('perfil');
        $nuevoRegistro->email = $request->input('email');
        $nuevoRegistro->estatus = 'ACT';
        $nuevoRegistro->id_departamento = $request->input('departamento');
       
        if($nuevoRegistro->save())
        {
            return response()->json(['mensaje' => 'Usuario guardado con éxito']);
        }else{
            return response()->json(['mensaje' => 'Error al Guardar Usuario']);
        }
    }

    public function editaUsuario($id_usuario, Request $request)
    {
        $usuario = Usuario::find($id_usuario);

        if (!$usuario) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $usuario -> usuario = $request->input('usuario');
        $usuario ->password  = $request->input('contrasena');
        $usuario ->perfil = $request->input('perfil');
        $usuario->email = $request->input('email');
        $usuario->estatus = $request->input('estatus');
        $usuario->id_departamento = $request->input('departamento');

        if($usuario->save())
        {
            return response()->json(['mensaje' => 'Usuario actualizado con éxito']);
        }else{
            return response()->json(['mensaje' => 'Error al Actualizar Usuario']);
        }

       
    }


    public function getUsuarioPorId($id_usuario)
    {
        $id_usuario = $id_usuario;
        $registros = Usuario::where('id_usuario', $id_usuario)->first();
        return response()->json($registros);
    }


    public function verificarCorreoExistente($correo)
    {
        $existe = Usuario::where('email', $correo)->exists();
        return response()->json(['existe' => $existe]);
    }
}
