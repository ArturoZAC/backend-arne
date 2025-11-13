<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TestMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Aquí podemos hacer algo antes de que llegue al controller

        return $next($request); // Pasamos la request al controller
    }
}
