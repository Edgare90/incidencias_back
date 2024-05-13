<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketArchivo;
use App\Models\TicketComentario;
use App\Models\TicketEstatus;
use App\Models\TicketDepartamento;
use App\Models\Estatus;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function GuardaTicket(Request $request)
    {
        $nuevoRegistro = new Ticket();
        $derivado = $request->input('derivado');
        if ($derivado === 'si') {
            $nuevoRegistro->ticket_anterior = $request->input('ticketAnterior');
        } else {
            $nuevoRegistro->ticket_anterior = 0;
        }

        $nuevoRegistro->fecha_alta = now();
        $nuevoRegistro->usr_alta = $request->input('id_usuario');
       
        if($nuevoRegistro->save())
        {
            $departamentos = $request->input('dirigidoA');
            //var_dump($departamentos); // Verifica qué valores se están pasando como departamentos
    
            if (!is_array($departamentos)) {
                $departamentos = [$departamentos];
            }
    
            foreach ($departamentos as $departamentoId) {
                try {
                    $ticketDepartamento = new TicketDepartamento();
                    $ticketDepartamento->id_ticket = $nuevoRegistro->id_ticket;
                    $ticketDepartamento->id_departamento = $departamentoId;
                    $ticketDepartamento->save();
                } catch (\Exception $e) {
                    $errorMessage = 'Error al guardar TicketDepartamento: ' . $e->getMessage();
                    return response()->json(['mensaje' => $errorMessage], 500);
                }
            }

            
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $archivo) {
                    // Generar un nombre único para el archivo
                    $nombreArchivo = uniqid('archivo_') . '.' . $archivo->getClientOriginalExtension();
        
                    // Guardar el archivo en la carpeta de almacenamiento
                    $rutaArchivo = $archivo->storeAs('archivos', $nombreArchivo, 'public');
        
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

    public function editaTicket(Request $request)
    {
        $idTicket = $request->input('idTicket');
        $departamentos = $request->input('dirigidoA');
        $todobien = 1;
        
        if( $departamentos != 0)
        {
            if (!is_array($departamentos)) {
                $departamentos = [$departamentos];
            }

            foreach ($departamentos as $departamentoId) {
                try {
                    $ticketDepartamento = new TicketDepartamento();
                    $ticketDepartamento->id_ticket = $idTicket;
                    $ticketDepartamento->id_departamento = $departamentoId;
                    $ticketDepartamento->save();
                } catch (\Exception $e) {
                    $errorMessage = 'Error al guardar TicketDepartamento: ' . $e->getMessage();
                    return response()->json(['mensaje' => $errorMessage], 500);
                }
            }
        }

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $archivo) {
                // Generar un nombre único para el archivo
                $nombreArchivo = uniqid('archivo_') . '.' . $archivo->getClientOriginalExtension();
    
                // Guardar el archivo en la carpeta de almacenamiento
                $rutaArchivo = $archivo->storeAs('archivos', $nombreArchivo, 'public');
    
                // Crear un nuevo registro en la tabla TicketArchivo
                $ticketArchivo = new TicketArchivo();
                $ticketArchivo->id_ticket = $idTicket; // Asignar el ID del nuevo registro de Ticket
                $ticketArchivo->archivo = $nombreArchivo; // Asignar el nombre único del archivo
                if (!$ticketArchivo->save()) {
                    return response()->json(['mensaje' => 'Error al guardar archivo']);
                }
            }
        }
        $derivado = $request->input('derivado');
        $usuario = $request->input('id_usuario');

        $nuevoComentario = new TicketComentario();
            $nuevoComentario->id_ticket =  $idTicket;
            $nuevoComentario->usr =   $usuario;
            $nuevoComentario->comentario= $request->input('comentario');
            $nuevoComentario->fecha_comentario = now();
            $nuevoComentario->save();

        
        if($derivado != 0)
        {
            $nuevoEstatus = new TicketEstatus();
            $nuevoEstatus->id_ticket =  $idTicket;
            $nuevoEstatus->id_estatus = $derivado;
            $nuevoEstatus->usr = $usuario;
            $nuevoEstatus->fecha = now();
            
            if(!$nuevoEstatus->save())
            {
                $todobien = 0;
            }

        }

        if($nuevoComentario->save() && $todobien == 1)
        {
            return response()->json(['mensaje' => 'Ticket editado con éxito']);
        }else
        {
            return response()->json(['mensaje' => 'Error al editar estatus o comentario']);
        }
        
    }


    public function GetTicketUser($id_usuario)
    {
        $tickets = Ticket::where('usr_alta', $id_usuario)
        ->with(['archivos', 'comentarios', 'estatus', 'usuario', 'departamentos'])
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
        ->with(['archivos', 'comentarios', 'estatus', 'estatus.estatusInfo', 'usuario', 'departamentos'])
        ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No se encontraron tickets para el id.'], 404);
        }
    
        return $tickets;
    }
}
