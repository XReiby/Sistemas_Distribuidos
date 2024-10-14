document.addEventListener('DOMContentLoaded', () => {
    fetch('/api/data') // Suponiendo que tienes una ruta API que devuelve datos
        .then(response => response.json())
        .then(data => {
            console.log(data); // Muestra los datos en la consola
            // Aquí puedes manipular el DOM para mostrar los datos en la vista
        })
        .catch(error => console.error('Error fetching data:', error));
});
