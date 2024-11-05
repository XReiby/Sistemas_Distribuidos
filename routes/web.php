<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('index');
});

Route::get('/api/current-leader', [MessageController::class, 'getCurrentLeader']);
Route::get('/api/messages', [MessageController::class, 'getMessages']);
Route::post('/api/messages', [MessageController::class, 'storeMessage']);
Route::post('/api/update-leader', [MessageController::class, 'updateLeader']);
