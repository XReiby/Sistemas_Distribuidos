// Espera a que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    fetchMessages(); // Llama a la función al cargar la página

    // Llama a fetchMessages cada 2 segundos para actualizar la lista
    setInterval(fetchMessages, 2000);
});

// Función para obtener mensajes
function fetchMessages() {
    fetch('/api/messages') // Solicitud a la API para obtener mensajes
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json(); // Convierte la respuesta a JSON
        })
        .then(data => {
            const messageList = document.getElementById('message-list'); // Obtiene la lista de mensajes
            messageList.innerHTML = ''; // Limpia la lista antes de agregar nuevos mensajes
            data.forEach(message => {
                const li = document.createElement('li'); // Crea un nuevo elemento de lista
                li.textContent = message; // Establece el contenido del mensaje
                messageList.appendChild(li); // Agrega el nuevo elemento a la lista
            });
        })
        .catch(error => console.error('Error fetching messages:', error)); // Manejo de errores
}
