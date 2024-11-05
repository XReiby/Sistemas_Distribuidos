<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MessageController extends Controller
{
    protected $currentLeader = 'node1'; // Nodo líder inicial
    protected $logFile; // Ruta del archivo de log

    public function __construct()
    {
        $this->logFile = storage_path('logs/mensajes.log'); // Define la ruta del archivo de log
    }

    // Actualiza el líder actual
    public function updateLeader(Request $request) {
        $this->currentLeader = $request->input('current_leader');
        // Registrar en los logs
        $logMessage = "[System] Leader updated to: {$this->currentLeader}\n";
        File::append($this->logFile, $logMessage);
        return response()->json(['status' => 'Líder actualizado', 'current_leader' => $this->currentLeader]);
    }

    // Obtiene el líder actual
    public function getCurrentLeader() {
        return response()->json(['current_leader' => $this->currentLeader]);
    }

    // Obtiene todos los mensajes
    public function getMessages() {
        return response()->json($this->readMessagesFromLog());
    }

    // Almacena un nuevo mensaje
    public function storeMessage(Request $request) {
        $request->validate([
            'sender_node' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'message' => 'required|string|max:255',
        ]);

        $message = [
            'sender_node' => $request->input('sender_node'),
            'type' => $request->input('type'),
            'message' => $request->input('message'),
            'timestamp' => now()->toISOString() // Agregar una marca de tiempo
        ];

        // Loguea el mensaje en el archivo
        $logMessage = "[{$message['timestamp']}] [{$message['sender_node']}] ({$message['type']}): {$message['message']}\n";
        File::append($this->logFile, $logMessage);

        return response()->json(['status' => 'Mensaje almacenado', 'message' => $message]);
    }

    // Lee los mensajes desde el archivo de log
    protected function readMessagesFromLog() {
        if (File::exists($this->logFile)) {
            $lines = File::lines($this->logFile);
            $messages = [];

            foreach ($lines as $line) {
                // Parsear el mensaje desde el formato de log
                if (preg_match('/\[(.*?)\] \[(.*?)\] \((.*?)\): (.*)/', $line, $matches)) {
                    $messages[] = [
                        'timestamp' => $matches[1],
                        'sender_node' => $matches[2],
                        'type' => $matches[3],
                        'message' => $matches[4],
                    ];
                }
            }

            return $messages;
        }

        return [];
    }
}
