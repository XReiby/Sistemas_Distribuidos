<?php
$host = '0.0.0.0';
$port = 8080;

// Crear un socket
$socket = socket_create(AF_INET, SOCK_STREAM, 0);
if ($socket === false) {
    die("Error al crear el socket: " . socket_strerror(socket_last_error()) . "\n");
}

// Opción para reutilizar la dirección (liberar el puerto cuando el servidor termina)
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

// Hacer bind al socket
if (socket_bind($socket, $host, $port) === false) {
    die("Error al hacer bind: " . socket_strerror(socket_last_error($socket)) . "\n");
}

// Escuchar conexiones
if (socket_listen($socket, 5) === false) {
    die("Error al escuchar: " . socket_strerror(socket_last_error($socket)) . "\n");
}

echo "Servidor escuchando en {$host}:{$port}\n";

while (true) {
    // Aceptar conexiones de cliente
    $client = socket_accept($socket);
    if ($client === false) {
        echo "Error al aceptar la conexión: " . socket_strerror(socket_last_error($socket)) . "\n";
        continue; // Continúa con la siguiente iteración del bucle
    }

    echo "Nuevo cliente conectado\n";

    // Leer el mensaje del cliente
    $input = socket_read($client, 1024);
    if ($input === false) {
        echo "Error al leer del socket: " . socket_strerror(socket_last_error($client)) . "\n";
        socket_close($client);
        continue;
    }

    echo "Recibido: $input\n";

    // Almacenar el mensaje en la API de Laravel
    $apiUrl = 'http://app:80/api/messages'; // Cambiar a la IP del contenedor si es necesario
    $data = json_encode(['message' => trim($input)]); // Prepara el mensaje

    // Configurar la solicitud cURL
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);
    curl_setopt($ch, CURLOPT_VERBOSE, true); // Habilitar el modo verbose para depuración

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Verificar si hubo un error en la solicitud cURL
    if ($response === false) {
        echo "Error en cURL: " . curl_error($ch) . "\n";
    } else {
        echo "Respuesta de la API: " . $response . "\n"; // Imprime la respuesta del controlador
    }

    curl_close($ch);

    // Enviar una respuesta al cliente
    $responseMessage = "Mensaje recibido y almacenado";
    socket_write($client, $responseMessage, strlen($responseMessage));
    socket_close($client);
}

// Cerrar el socket principal
socket_close($socket);
