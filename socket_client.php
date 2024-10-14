<?php
$host = 'app'; // Cambia 127.0.0.1 a 'app'
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "Error al crear el socket: " . socket_strerror(socket_last_error()) . "\n";
    exit();
}

echo "Conectando al servidor de sockets...\n";
$result = socket_connect($socket, $host, $port);
if ($result === false) {
    echo "Error al conectar: " . socket_strerror(socket_last_error($socket)) . "\n";
    exit();
}

$message = "¡Hola desde el nodo!";
socket_write($socket, $message, strlen($message));

echo "Mensaje enviado: $message\n";

socket_close($socket);
