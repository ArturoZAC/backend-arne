<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/usuarios', function () {
    return response()->json([
        'mensaje' => '✅ API funcionando correctamente',
        'usuarios' => [
            ['id' => 1, 'nombre' => 'Arturo'],
            ['id' => 2, 'nombre' => 'Bryan'],
        ]
    ]);
});

// ===========================
//  AUTH
// ===========================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt.auth')->get('/renewToken', [AuthController::class, 'renewToken']);
Route::get('/prueba', [TestController::class, 'metodo']);

// ===========================
//  PRODUCTS
// ===========================

// Crear
Route::post('/products', [ProductController::class, 'store']);

// Obtener todos
Route::get('/products', [ProductController::class, 'index']);

// Obtener uno (ID)
Route::get('/products/{id}', [ProductController::class, 'show']);

// Actualizar (POST porque hosting)
Route::post('/products/{id}/update', [ProductController::class, 'update']);

// Eliminar (POST porque hosting)
Route::post('/products/{id}/delete', [ProductController::class, 'destroy']);
