<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
       // dd('Punto de depuración alcanzado');
        Log::info('Punto de depuración alcanzado');
        if (Auth::attempt($credentials)) {
            // Autenticación exitosa
            $user = Auth::user();
           // dd($user);
            $token = $request->user()->createToken($user->usuario);

            $additionalData = [
                'id_usuario' => $user->id_usuario,
                'perfil' => $user->perfil,
                'id_departamento' => $user->id_departamento,
                'token' => $token->plainTextToken,
            ];


            return response()->json(['message' => 'Login successful', 'data' => $additionalData], 200);
        } else {
            // Autenticación fallida
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}