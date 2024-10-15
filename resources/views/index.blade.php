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
</body>
</html>
