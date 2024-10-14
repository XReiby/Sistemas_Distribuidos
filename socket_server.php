<?php
$host = '0.0.0.0';
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, 0);
socket_bind($socket, $host, $port);
socket_listen($socket);

echo "Servidor escuchando en {$host}:{$port}\n";

$clients = [];

while (true) {
    $client = socket_accept($socket);
    if ($client) {
        $clients[] = $client;
        echo "Nuevo cliente conectado\n";

        // Lee el mensaje del cliente
        $input = socket_read($client, 1024);
        echo "Recibido: $input\n";

        // Envía una respuesta al cliente
        $response = "Mensaje recibido";
        socket_write($client, $response, strlen($response));
        socket_close($client);
    }
}

socket_close($socket);
