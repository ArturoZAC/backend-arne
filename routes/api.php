<?php

use App\Http\Controllers\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/usuarios', function () {
    return response()->json([
        'mensaje' => '✅ API funcionando correctamente',
        'usuarios' => [
            ['id' => 1, 'nombre' => 'Arturo'],
            ['id' => 2, 'nombre' => 'Bryan'],
        ]
    ]);
});

// Endpoint para registrar usuarios
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Ruta protegida con JWT
Route::middleware('jwt.auth')->get('/renewToken', [AuthController::class, 'renewToken']);
Route::get('/prueba', [TestController::class, 'metodo']);