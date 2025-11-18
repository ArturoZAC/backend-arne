<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Factory;

class FirebaseJwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Token requerido'], 401);
        }

        $token = substr($authHeader, 7);

        try {
            $auth = (new Factory)
                ->withServiceAccount(storage_path('app/firebase_credentials.json'))
                ->createAuth();

            $verifiedIdToken = $auth->verifyIdToken($token);

            // Puedes guardar el UID del usuario en el request
            $request->merge([
                'uid' => $verifiedIdToken->claims()->get('sub')
            ]);

        } catch (InvalidToken $e) {
            return response()->json(['message' => 'Token inválido'], 401);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Error al procesar token'], 401);
        }

        return $next($request);
    }
}
