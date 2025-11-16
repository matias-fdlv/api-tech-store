<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// RUTAS PÚBLICAS DE PRODUCTOS
Route::prefix('products')->group(function () {
    // GET /api/products → Listar todos los productos (con filtros)
    Route::get('/', [ProductController::class, 'index']);
    
    // GET /api/products/{id} → Mostrar un producto específico
    Route::get('/{id}', [ProductController::class, 'show']);
    
    // 🆕 GET /api/products/specials/offers → Productos en oferta
    Route::get('/specials/offers', [ProductController::class, 'offers']);
});

// Ruta de prueba
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => '¡API de Tech Store funcionando correctamente!',
        'timestamp' => now()
    ]);
});