<?php

use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Models\Ticket;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\StorageController;


            Route::post('/login', [LoginController::class, 'login'])->name('login');

            Route::middleware('auth:sanctum')->group(function () {
            Route::get('verificar-usuario-existente/{usuario}', [UsuarioController::class, 'verificarUsuarioExistente']);
            Route::get('getPerfiles',[UsuarioController::class, 'getPerfiles']);
            Route::post('guarda-usuario',[UsuarioController::class, 'GuardaUsuario']);
            Route::get('getUsuarios',[UsuarioController::class,'getUsuarios']);
            Route::get('getUsuarioPorId/{id_usuario}', [UsuarioController::class,'getUsuarioPorId']);
            Route::get('verificar-correo-existente/{email}',[UsuarioController::class, 'verificarCorreoExistente']);
            Route::put('edita-usuario/{id_usuario}',[UsuarioController::class,'editaUsuario']);


            Route::get('get-perfil-lista',[PerfilController::class,'getPerfiles']);
            Route::put('edita-perfil',[PerfilController::class, 'editaPerfil']);

            Route::get('get-deptos',[DepartamentoController::class,'getDeptos']);
            Route::post('guarda-depto',[DepartamentoController::class,'saveDeptos']);


            Route::post('guarda-ticket',[TicketController::class,'GuardaTicket']);
            Route::post('edita-ticket',[TicketController::class,'editaTicket']);
            Route::get('obtiene-tickets-user/{id_usuario}', [TicketController::class,'GetTicketUser']);
            Route::get('obtiene-tickets-id/{id_ticket}',[TicketController::class,'GetTicketId']);
            Route::get('obtiene-estatus',[TicketController::class,'GetStatus']);
            Route::get('/storage/{filename}', [StorageController::class, 'getImage']);
            });
