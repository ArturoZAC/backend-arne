<?php

use App\Http\Controllers\ProductController;
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

// Crear
Route::post('/products', [ProductController::class, 'store']);

// Obtener todos
Route::get('/products', [ProductController::class, 'index']);

// Obtener uno
Route::get('/products/{id}', [ProductController::class, 'show']);

// Actualizar (usando POST + _method=PUT)
Route::post('/products/{id}/update', [ProductController::class, 'update']);

// Eliminar (usando POST + _method=DELETE)
Route::post('/products/{id}/delete', [ProductController::class, 'destroy']);

// routes/api.php
Route::get('/productos/{slug}', [ProductController::class, 'getBySlug']);
