<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

// Ruta para la vista principal
Route::get('/', function () {
    return view('index'); // Devuelve la vista 'index.blade.php'
});

// Ruta para obtener mensajes
Route::get('/api/messages', [MessageController::class, 'index']); // Devuelve la lista de mensajes

// Ruta para almacenar un nuevo mensaje (si decides implementarla)
Route::post('/api/messages', [MessageController::class, 'store']);
