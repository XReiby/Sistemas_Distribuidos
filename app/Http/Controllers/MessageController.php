<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messages = []; // Almacena mensajes en memoria (para fines de ejemplo)

    public function index()
    {
        return response()->json($this->messages); // Retorna los mensajes en formato JSON
    }

    public function store(Request $request)
    {
        $this->messages[] = $request->input('message'); // Agrega un nuevo mensaje
        return response()->json(['success' => true]);
    }
}
