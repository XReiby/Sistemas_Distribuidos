document.addEventListener('DOMContentLoaded', function () {
    // Llama a fetchMessages cada 2 segundos
    setInterval(fetchMessages, 2000);

    // Función para obtener los mensajes
    function fetchMessages() {
        fetch('/api/messages')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la red');
                }
                return response.json();
            })
            .then(data => {
                console.log('Data received from API:', data); // Para verificar los datos

                // Aquí actualizamos la lista de mensajes en la página
                const messageList = document.getElementById('message-list');
                messageList.innerHTML = ''; // Limpia la lista antes de agregar nuevos mensajes

                if (Array.isArray(data)) {  // Verifica que data sea un arreglo
                    data.forEach(message => {
                        const li = document.createElement('li');
                        li.textContent = message;
                        messageList.appendChild(li);
                    });
                } else {
                    console.error('La respuesta de la API no es un arreglo:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching messages:', error);
            });
    }
});
