<?php
$host = 'app'; // Dirección del servidor
$port = 8080;
sleep(10); // Espera antes de iniciar

function sendMessage($messageType, $messageContent) {
    global $host, $port;
    $messageData = [
        'type' => $messageType,
        'sender_node' => 'node3', // Nombre único para cada nodo
        'message' => $messageContent
    ];

    $socket = socket_create(AF_INET, SOCK_STREAM, 0);
    if (@socket_connect($socket, $host, $port)) {
        socket_write($socket, json_encode($messageData), strlen(json_encode($messageData)));
        socket_close($socket);
    } else {
        echo "Error al conectar con el servidor\n"; // Depuración: Error de conexión
    }
}

function receiveMessages() {
    global $host, $port;

    $socket = socket_create(AF_INET, SOCK_STREAM, 0);
    if (@socket_connect($socket, $host, $port)) {
        while (true) {
            $input = socket_read($socket, 1024);
            if ($input !== false) {
                $messageData = json_decode($input, true);
                if ($messageData) {
                    switch ($messageData['type']) {
                        case 'leadership_proposal':
                            echo "Recibido: Propuesta de liderazgo\n";
                            sendMessage('confirmation', 'Confirmo la propuesta de liderazgo');
                            break;
                        case 'confirmation':
                            echo "Recibido: Confirmación de nuevo líder\n";
                            break;
                        case 'failure_notification':
                            echo "Recibido: Notificación de fallo\n";
                            break;
                    }
                }
            }
        }
        socket_close($socket);
    } else {
        echo "Error al conectar al socket\n"; // Depuración: Error de conexión
    }
}

// Iniciar el receptor de mensajes en un hilo separado (o puedes llamarlo de forma directa en este caso)
receiveMessages();

// Enviar una propuesta de liderazgo cada 30 segundos para mantener la comunicación activa
while (true) {
    sendMessage('leadership_proposal', 'Propuesta para ser líder');
    sleep(30);
}
