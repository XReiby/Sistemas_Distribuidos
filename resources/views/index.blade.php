<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Aplicación</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <header>
        <h1>Bienvenido a mi Aplicación</h1>
    </header>
    <main>
        <p>Contenido principal de la aplicación.</p>
        <h2>Mensajes de los Nodos:</h2>
        <ul id="message-list"></ul> <!-- Lista para mostrar mensajes -->
    </main>
    <footer>
        <p>&copy; 2024 Mi Aplicación</p>
    </footer>
    <script>
        // Función para obtener mensajes
        function fetchMessages() {
            fetch('/api/messages')
                .then(response => response.json())
                .then(data => {
                    const messageList = document.getElementById('message-list');
                    messageList.innerHTML = ''; // Limpiar la lista antes de agregar nuevos mensajes
                    data.forEach(message => {
                        const li = document.createElement('li');
                        li.textContent = message;
                        messageList.appendChild(li);
                    });
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        // Llamar a fetchMessages cada 2 segundos
        setInterval(fetchMessages, 2000);
    </script>
</body>
</html>
