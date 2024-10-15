<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

// Ruta para la vista principal (opcional)
Route::get('/', function () {
    return view('index'); // Devuelve la vista 'index.blade.php'
});

// Ruta para obtener los mensajes
Route::get('/api/messages', [MessageController::class, 'index']);

// Ruta para almacenar un nuevo mensaje
Route::post('/api/messages', [MessageController::class, 'store']);
