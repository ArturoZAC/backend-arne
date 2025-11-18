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

    public function refreshToken(Request $request)
    {
        $uid = $request->uid; // Lo enviamos desde el middleware

        $auth = (new Factory)
            ->withServiceAccount(storage_path('app/firebase_credentials.json'))
            ->createAuth();

        // Crear un token personalizado nuevo
        $newToken = $auth->createCustomToken($uid)->toString();

        return response()->json([
            'token' => $newToken
        ]);
    }

}
