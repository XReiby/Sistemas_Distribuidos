<?php
$host = 'app';
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    die("Error al crear el socket: " . socket_strerror(socket_last_error()) . "\n");
}

echo "Conectando al servidor de sockets...\n";
$result = socket_connect($socket, $host, $port);
if ($result === false) {
    die("Error al conectar: " . socket_strerror(socket_last_error($socket)) . "\n");
}

$message = "¡Hola desde el nodo!";
socket_write($socket, $message, strlen($message));

echo "Mensaje enviado: $message\n";

socket_close($socket);
