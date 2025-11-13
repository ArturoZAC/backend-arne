<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Middleware globales para todas las requests.
     */
    protected $middleware = [
        // Aquí van los middleware globales si quieres
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Grupos de middleware
     */
    protected $middlewareGroups = [
        'web' => [
            // middleware para rutas web si los tuvieras
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middleware que puedes usar en rutas individualmente
     */
protected $routeMiddleware = [
    'jwt.auth' => \App\Http\Middleware\FirebaseJwtMiddleware::class,
    'test' => \App\Http\Middleware\TestMiddleware::class,

];

}
