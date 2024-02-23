<?php

use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\PerfilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

//Route::group(['middleware' => ['cors']], function () {
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
//});



