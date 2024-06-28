<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketArchivo;
use App\Models\TicketComentario;
use App\Models\TicketEstatus;
use App\Models\TicketDepartamento;
use App\Models\Estatus;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketCreatedMail; 

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
            //var_dump($departamentos);
    
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
                    $nombreArchivo = uniqid('archivo_') . '.' . $archivo->getClientOriginalExtension();
        
                    $rutaArchivo = $archivo->storeAs('archivos', $nombreArchivo, 'public');
        
                    $ticketArchivo = new TicketArchivo();
                    $ticketArchivo->id_ticket = $nuevoRegistro->id_ticket;
                    $ticketArchivo->archivo = $nombreArchivo;
                    if (!$ticketArchivo->save()) {
                        return response()->json(['mensaje' => 'Error al guardar archivo']);
                    }
                }
            }

            $nuevoComentario = new TicketComentario();
            $nuevoComentario->id_ticket =  $nuevoRegistro->id_ticket;
            $nuevoComentario->usr =  $request->input('id_usuario');
            $nuevoComentario->comentario= $request->input('comentario');
            $nuevoComentario->fecha_comentario = now();

            $nuevoEstatus = new TicketEstatus();
            $nuevoEstatus->id_ticket =  $nuevoRegistro->id_ticket;
            $nuevoEstatus->id_estatus =  1;
            $nuevoEstatus->usr = $request->input('id_usuario');
            $nuevoEstatus->fecha = now();
            

            if($nuevoComentario->save() && $nuevoEstatus->save())
            {
                $user = Usuario::find($nuevoRegistro->usr_alta);
                if (!$user) {
                    return response()->json(['mensaje' => 'Usuario no encontrado']);
                }
                $userEmails = Usuario::whereIn('id_departamento', $departamentos)->pluck('email')->toArray();

                    //https://mailtrap.io/inboxes/2978452/messages/4295568497
                    if (!empty($userEmails)) {
                        Mail::to($userEmails)
                            ->send(new TicketCreatedMail($nuevoRegistro, $user));
                        return response()->json(['mensaje' => 'Ticket guardado y correos enviados con éxito']);
                    } else {
                        return response()->json(['mensaje' => 'Ticket guardado pero no se encontraron usuarios para enviar correos']);
                    }

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
                $nombreArchivo = uniqid('archivo_') . '.' . $archivo->getClientOriginalExtension();
    
                $rutaArchivo = $archivo->storeAs('archivos', $nombreArchivo, 'public');
    
                $ticketArchivo = new TicketArchivo();
                $ticketArchivo->id_ticket = $idTicket;
                $ticketArchivo->archivo = $nombreArchivo;
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
            $existentesDepartamentos = TicketDepartamento::where('id_ticket', $idTicket)->pluck('id_departamento')->toArray();
            $departamentos = array_unique(array_merge($existentesDepartamentos, $departamentos));
            $userEmails = Usuario::whereIn('id_departamento', $departamentos)->pluck('email')->toArray();

            if ($nuevoComentario->save() && $todobien == 1) {
                
                $tickets = Ticket::where('id_ticket', $idTicket)
                ->with(['archivos', 'comentarios', 'estatus', 'usuario', 'departamentos'])
                ->first();

                $user = Usuario::find($usuario);
                if (!$user) {
                    return response()->json(['mensaje' => 'Usuario no encontrado']);
                }

                if ($tickets && $user) {
                    Mail::to($userEmails)->send(new TicketCreatedMail($tickets,  $user, 'updated'));
                    return response()->json(['mensaje' => 'Ticket editado con éxito y correos enviados']);
                } else {
                    return response()->json(['mensaje' => 'Error al encontrar el ticket especificado']);
                }
            } else {
                return response()->json(['mensaje' => 'Error al editar estatus o comentario']);
            }
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

    public function GetTicketDepto($id_depto)
    {
        $tickets = Ticket::whereHas('departamentos', function ($query) use ($id_depto) {
            $query->where('ticket_departamentos.id_departamento', '=', $id_depto);
        })->with(['usuario', 'departamentos', 'comentarios', 'estatus'])->get();
    
        return response()->json($tickets);
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
        ->with(['archivos', 'comentarios.usuario', 'estatus', 'estatus.estatusInfo', 'usuario', 'departamentos'])
        ->get();

        if ($tickets->isEmpty()) {
            return response()->json(['message' => 'No se encontraron tickets para el id.'], 404);
        }
    
        return $tickets;
    }
}
