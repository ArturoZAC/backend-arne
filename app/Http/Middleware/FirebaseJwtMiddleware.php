<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class FirebaseJwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Obtener el token del header Authorization
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Token requerido'
            ], 401);
        }

        try {
            // Decodificar el token
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));

            // Guardar el id del usuario en la request
            $request->attributes->set('user_id', $decoded->sub);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Token inválido',
                'detalle' => $e->getMessage()
            ], 401);
        }

        return $next($request);
    }
}
