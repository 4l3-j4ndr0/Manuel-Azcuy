// Script para cargar las imágenes del footer dinámicamente
document.addEventListener("DOMContentLoaded", function () {
    // Realiza la solicitud a load_footer_images.php
    fetch('assets/config/load_footer_images.php')
        .then(response => {
            // Verifica que la respuesta sea exitosa
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.text();
        })
        .then(html => {
            // Inserta el contenido HTML recibido en el div del footer
            const footerGallery = document.getElementById('footerGallery');
            if (footerGallery) {
                footerGallery.innerHTML = html;
            } else {
                console.error('No se encontró el contenedor footerGallery');
            }
        })
        .catch(error => {
            // Maneja cualquier error que ocurra
            console.error('Error al cargar las imágenes del footer:', error);
        });
});
