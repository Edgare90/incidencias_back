<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;

Route::group(['middleware' => ['cors']], function () {
    Route::get('verificar-usuario-existente/{usuario}', [UsuarioController::class, 'verificarUsuarioExistente']);
    Route::get('getPerfiles',[UsuarioController::class, 'getPerfiles']);
});



