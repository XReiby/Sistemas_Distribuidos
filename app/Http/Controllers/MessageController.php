<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log; // Asegúrate de importar el facade Log

class MessageController extends Controller
{
    protected $messages = []; // Almacena mensajes en memoria (para fines de ejemplo)

    // Método para obtener los mensajes
    public function index()
    {
        return response()->json($this->messages); // Retorna la lista de mensajes en formato JSON
    }

    // Método para almacenar un nuevo mensaje
    public function store(Request $request)
    {
        Log::info('Received request to store message: ', $request->all()); // Log the incoming request

        try {
            // Validar que el mensaje esté presente
            $request->validate([
                'message' => 'required|string|max:255',
            ]);

            // Almacena el mensaje
            $this->messages[] = $request->input('message'); // Agrega un nuevo mensaje
            Log::info('Message stored successfully.'); // Log successful storage
            return response()->json(['success' => true, 'messages' => $this->messages]);
        } catch (\Exception $e) {
            Log::error('Error storing message: ' . $e->getMessage()); // Log any errors
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
