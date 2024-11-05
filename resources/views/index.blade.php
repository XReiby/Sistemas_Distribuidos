<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Nodos</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <header>
        <h1>Aplicación de Nodos Distribuidos</h1>
    </header>
    <main>
        <h2>Nodo Líder Actual: <span id="current-leader"></span></h2>
        <h3>Mensajes Entre Nodos:</h3>
        <ul id="message-list"></ul>
    </main>
    <footer>
        <p>&copy; 2024 Monitor de Nodos</p>
    </footer>
</body>
</html>
