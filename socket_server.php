<?php
$host = '0.0.0.0';
$port = 8080;

$socket = socket_create(AF_INET, SOCK_STREAM, 0);
if ($socket === false) {
    die("Error al crear el socket: " . socket_strerror(socket_last_error()) . "\n");
}

socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

if (socket_bind($socket, $host, $port) === false) {
    die("Error al hacer bind: " . socket_strerror(socket_last_error($socket)) . "\n");
}

if (socket_listen($socket, 5) === false) {
    die("Error al escuchar: " . socket_strerror(socket_last_error($socket)) . "\n");
}

echo "Servidor Token Ring activo en {$host}:{$port}\n";

$nodes = ['node1', 'node2', 'node3', 'node4', 'node5'];
$currentLeader = $nodes[0];
$tokenHolder = 0;

$leaderFailureInterval = 60; // 60 segundos para el fallo
$lastLeaderFailureTime = time();

while (true) {
    echo "Esperando conexión...\n";

    // Comprobación periódica de fallo del líder
    if (time() - $lastLeaderFailureTime >= $leaderFailureInterval) {
        echo "El líder {$currentLeader} ha fallado (tiempo transcurrido: " . (time() - $lastLeaderFailureTime) . "s)\n";
        handleFailure($currentLeader);
        $lastLeaderFailureTime = time();
    }

    // Aceptar conexión del cliente
    $client = socket_accept($socket);
    if ($client === false) {
        echo "No se pudo aceptar la conexión\n";
        continue;
    } else {
        echo "Conexión aceptada\n";
    }

    $input = socket_read($client, 1024);
    if ($input === false) {
        echo "Error al leer del cliente\n";
        socket_close($client);
        continue;
    }

    echo "Mensaje recibido: $input\n";

    $messageData = json_decode($input, true);
    if ($messageData) {
        echo "Procesando mensaje de {$messageData['sender_node']}...\n";

        switch ($messageData['type']) {
            case 'detection_failure':
                handleFailure($messageData['failed_node']);
                break;

            case 'leadership_proposal':
                if ($messageData['sender_node'] !== $currentLeader) {
                    startElection($messageData['sender_node']);
                }
                break;

            case 'confirmation':
                $currentLeader = $messageData['new_leader'];
                updateLeaderOnWeb($currentLeader);
                break;
        }

        // Eliminar el registro en el archivo
        // $logMessage = "[{$messageData['sender_node']}] ({$messageData['type']}): {$messageData['message']}\n";
        // file_put_contents($logFile, $logMessage, FILE_APPEND);

        sendToApi($messageData);
    } else {
        echo "No se pudo decodificar el mensaje JSON\n";
    }

    socket_write($client, "Mensaje recibido y procesado", strlen("Mensaje recibido y procesado"));
    socket_close($client);

    // Transferir token al siguiente nodo
    $tokenHolder = ($tokenHolder + 1) % count($nodes);
    echo "Token transferido a {$nodes[$tokenHolder]}\n";
}

socket_close($socket);

function handleFailure($failedNode) {
    global $currentLeader;
    if ($failedNode === $currentLeader) {
        echo "Fallo detectado en el líder: " . $currentLeader . "\n";
        startElection();
    }
}

function startElection($proposerNode = null) {
    global $nodes, $tokenHolder, $currentLeader;

    echo "Iniciando proceso de elección...\n";
    $newLeaderIndex = ($tokenHolder + 1) % count($nodes);
    $newLeader = $nodes[$newLeaderIndex];

    sendToAllNodes([
        'type' => 'confirmation',
        'new_leader' => $newLeader,
        'message' => "Nuevo líder elegido: {$newLeader}"
    ]);

    $currentLeader = $newLeader;
    updateLeaderOnWeb($currentLeader);
}

function sendToAllNodes($messageData) {
    global $nodes;
    foreach ($nodes as $node) {
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        if (@socket_connect($socket, $node, 8080)) {
            socket_write($socket, json_encode($messageData), strlen(json_encode($messageData)));
            socket_close($socket);
        } else {
            echo "Error al conectar con el nodo {$node}\n";
        }
    }
}

function sendToApi($messageData) {
    $apiUrl = 'http://app/api/messages';
    $data = json_encode($messageData);

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch) . "\n";
    } else {
        echo 'Respuesta de la API: ' . $response . "\n";
    }

    curl_close($ch);
}

function updateLeaderOnWeb($newLeader) {
    $apiUrl = 'http://app/api/update-leader';
    $data = json_encode(['current_leader' => $newLeader]);

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($data)
    ]);

    curl_exec($ch);
    curl_close($ch);
}
