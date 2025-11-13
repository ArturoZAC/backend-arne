<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'mensaje' => 'Usuario creado correctamente',
            'user' => $user
        ], 201);
    }

    // Nuevo endpoint login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Contraseña incorrecta'
            ], 401);
        }

        // Generar token JWT con id del usuario
        $payload = [
            'iss' => "laravel-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + env('JWT_TTL', 3600)
        ];

        $jwt = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'mensaje' => 'Login exitoso',
            'token' => $jwt,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

public function renewToken(Request $request)
{
    try {
        // Obtenemos el user_id desde la request (lo puso el middleware)
        $userId = $request->attributes->get('user_id');

        if (!$userId) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Token no válido o no proporcionado'
            ], 401);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Usuario no encontrado'
            ], 404);
        }

        // Asegurarnos que JWT_TTL sea un entero
        $ttl = intval(env('JWT_TTL', 3600));

        // Generamos un nuevo token
        $newPayload = [
            'iss' => "laravel-jwt",
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + $ttl
        ];

        $newToken = JWT::encode($newPayload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'mensaje' => 'Token renovado correctamente',
            'token' => $newToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    } catch (\Firebase\JWT\ExpiredException $e) {
        return response()->json([
            'error' => true,
            'mensaje' => 'El token ha expirado'
        ], 401);
    } catch (\Firebase\JWT\SignatureInvalidException $e) {
        return response()->json([
            'error' => true,
            'mensaje' => 'Firma del token inválida'
        ], 401);
    } catch (\Exception $e) {
        return response()->json([
            'error' => true,
            'mensaje' => 'Error al renovar token: ' . $e->getMessage()
        ], 500);
    }
}

}
