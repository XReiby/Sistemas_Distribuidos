<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class MessageController extends Controller
{
    protected $filePath = '/var/www/html/storage/messages.txt'; // Ruta al archivo .txt

    // Método para obtener los mensajes
    public function index()
    {
        $messages = []; // Inicializa el array de mensajes

        // Verifica si el archivo existe y es un archivo regular
        if (File::exists($this->filePath) && File::isFile($this->filePath)) {
            // Lee el contenido del archivo
            $content = File::get($this->filePath);
            // Divide el contenido en líneas y las guarda en el array
            $messages = array_filter(explode(PHP_EOL, trim($content))); // Usa array_filter para eliminar líneas vacías
        }

        return response()->json($messages); // Retorna los mensajes como JSON
    }

    // Método para almacenar un nuevo mensaje
    public function store(Request $request)
    {
        Log::info('Received request to store message: ', $request->all());

        try {
            // Validar que el mensaje esté presente
            $request->validate([
                'message' => 'required|string|max:255',
            ]);

            $message = $request->input('message');

            // Crear el archivo si no existe
            if (!File::exists($this->filePath)) {
                File::put($this->filePath, ""); // Crea el archivo si no existe
            }

            // Agrega el nuevo mensaje al archivo
            File::append($this->filePath, $message . PHP_EOL); // Almacena el mensaje

            Log::info('Message stored successfully.');

            // Lee nuevamente todos los mensajes para devolverlos
            $messages = $this->index()->getOriginalContent();

            return response()->json(['success' => true, 'messages' => $messages]);
        } catch (\Exception $e) {
            Log::error('Error storing message: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
