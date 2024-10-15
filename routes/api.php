<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::get('/api/messages', [MessageController::class, 'index']); // Ruta para obtener los mensajes
Route::post('/api/messages', [MessageController::class, 'store']); // Ruta para almacenar un nuevo mensaje

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
