<?php
$host = 'app';
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, 0);
if ($socket === false) {
    die("Error al crear el socket: " . socket_strerror(socket_last_error()) . "\n");
}

if (socket_connect($socket, $host, $port) === false) {
    die("Error al conectar al servidor: " . socket_strerror(socket_last_error($socket)) . "\n");
}

$messageData = [
    'sender_node' => 'node1',
    'type' => 'detection_failure',
    'message' => 'Fallo detectado en el nodo líder'
];

$message = json_encode($messageData);
socket_write($socket, $message, strlen($message));

$response = socket_read($socket, 1024);
echo "Respuesta del servidor: $response\n";

socket_close($socket);
