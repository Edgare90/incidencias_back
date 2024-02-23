<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Departamentos;

class DepartamentoController extends Controller
{
    public function getDeptos()
    {
        $registros = Departamentos::all();
        return response()->json($registros);
    }


    public function saveDeptos(Request $request)
    {
        $nuevoRegistro = new Departamentos;

        $nuevoRegistro->departamento = $request->input('departamento');
        $nuevoRegistro->estatus = $request->input('estatus');
       
        if($nuevoRegistro->save())
        {
            return response()->json(['mensaje' => 'Departamento guardado con éxito']);
        }else{
            return response()->json(['mensaje' => 'Error al Guardar Departamento']);
        }
    }
}
