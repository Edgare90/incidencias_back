<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketArchivo;
use App\Models\TicketComentario;
use App\Models\TicketEstatus;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function GuardaTicket(Request $request)
    {
        $nuevoRegistro = new Ticket();
        $nuevoRegistro->dirigido_a = $request->input('dirigidoA');
        $nuevoRegistro->fecha_alta = now();
        $nuevoRegistro->usr_alta = $request->input('id_usuario');;
       
        if($nuevoRegistro->save())
        {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $archivo) {
                    // Generar un nombre único para el archivo
                    $nombreArchivo = uniqid('archivo_') . '.' . $archivo->getClientOriginalExtension();
        
                    // Guardar el archivo en la carpeta de almacenamiento
                    $rutaArchivo = $archivo->storeAs('archivos', $nombreArchivo);
        
                    // Crear un nuevo registro en la tabla TicketArchivo
                    $ticketArchivo = new TicketArchivo();
                    $ticketArchivo->id_ticket = $nuevoRegistro->id_ticket; // Asignar el ID del nuevo registro de Ticket
                    $ticketArchivo->archivo = $nombreArchivo; // Asignar el nombre único del archivo
                    if (!$ticketArchivo->save()) {
                        return response()->json(['mensaje' => 'Error al guardar archivo']);
                    }
                }
            }

            $nuevoComentario = new TicketComentario();
            $nuevoComentario->id_ticket =  $nuevoRegistro->id_ticket;
            $nuevoComentario->usr =  1;
            $nuevoComentario->comentario= $request->input('comentario');
            $nuevoComentario->fecha_comentario = now();

            $nuevoEstatus = new TicketEstatus();
            $nuevoEstatus->id_ticket =  $nuevoRegistro->id_ticket;
            $nuevoEstatus->id_estatus =  1;
            $nuevoEstatus->usr = 1;
            $nuevoEstatus->fecha = now();
            

            if($nuevoComentario->save() && $nuevoEstatus->save())
            {
                return response()->json(['mensaje' => 'Ticket guardado con éxito']);
            }else
            {
                return response()->json(['mensaje' => 'Error al guardar estatus o comentario']);
            }
            
        }else{
            return response()->json(['mensaje' => 'Error al Guardar Ticket']);
        }
    }


    public function GetTicketUser($id_usuario)
    {
        $tickets = Ticket::where('usr_alta', $id_usuario)
        ->with(['archivos', 'comentarios', 'estatus', 'usuario', 'departamento'])
        ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No se encontraron tickets para el usuario.'], 404);
        }
    
        return $tickets;
    }

    public function GetStatus()
    {
        $estatus = Estatus::all();

        if ($estatus->isEmpty()) {
            return response()->json(['message' => 'No se encontraron estatus.'], 404);
        }
    
        return $estatus;
    }
     


    public function GetTicketId($id_ticket)
    {
        $tickets = Ticket::where('id_ticket', $id_ticket)
        ->with(['archivos', 'comentarios', 'estatus', 'estatus.estatusInfo', 'usuario', 'departamento'])
        ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No se encontraron tickets para el id.'], 404);
        }
    
        return $tickets;
    }
}
