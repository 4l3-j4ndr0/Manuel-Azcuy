// Cuando el DOM está completamente cargado
$(document).ready(function () {
    let currentPage = 1; // Página inicial
    let serie = null; // Serie inicial (será definida según el archivo HTML)

    // Detectar el parámetro "serie" 
    serie = $('body').data('serie'); // Extraemos el valor del parámetro "serie"
    console.log(`Serie actual: ${serie}`); // Solo para depuración

    // Función para cargar imágenes mediante AJAX
    function cargarImagenes(serie, page = 1) {
        $.ajax({
            url: 'assets/config/series_carga_dinamica_imagenes.php',
            type: 'GET', // Método HTTP para la solicitud
            data: { page: page, serie: serie },
            success: function (data) {
                // Inserta el contenido devuelto (imágenes) en el contenedor de la galería
                $('#galleryContainer').html(data);
                $('#galleryContainer').imagesLoaded().done(function () {
                    // Inicializar o re-inicializar Isotope
                    var $grid = $('#galleryContainer').isotope({
                        itemSelector: '.gallery-item', // Selección de elementos
                        layoutMode: 'fitRows', // Modo de diseño
                    });

                    // Recargar los elementos para actualizar el diseño
                    $grid.isotope('reloadItems').isotope();
                });

                currentPage = page; // Actualizar la página actual
            },
            error: function (xhr, status, error) {
                console.error('Error cargando imágenes:', error); // Muestra el error en caso de fallo
            }
        });
    }

     // Función para cargar la paginación mediante AJAX
     function cargarPaginacion(serie, page = 1) {
        $.ajax({
            url: 'assets/config/series_cargar_paginacion_gallery.php?serie=' + encodeURIComponent(serie) + '&page=' + page,
            type: 'GET', // Método HTTP para la solicitud
            success: function (data) {
                // Inserta el contenido devuelto (elementos <li>) en el <ul> de paginación
                $('#paginationContainer').html(data);
                // Esperar a que las imágenes se carguen completamente antes de inicializar Isotope
                
            },
            error: function (xhr, status, error) {
                console.error('Error cargando la paginación:', error); // Muestra el error en caso de fallo
            }
        });
    }

    // Evento para manejar el clic en la paginación
    $('#paginationContainer').on('click', 'a', function (event) {
        event.preventDefault(); // Evita que el enlace recargue la página
        const page = $(this).data('page'); // Obtiene el número de página del atributo "data-page"
        currentPage = page; // Actualiza la página actual
        cargarImagenes(serie, currentPage); // Vuelve a cargar imágenes para la nueva página
        cargarPaginacion(serie, currentPage); // Vuelve a cargar la paginación para la nueva página
    });

    // Seleccionar el modal y los elementos de la imagen
    var modal = document.getElementById("imageModal");
    var modalImg = document.getElementById("modalImage");
    var captionText = document.getElementById("modalCaption");

    // Función para abrir el modal
    function openModal(imageData) {
        modal.style.display = "block";
        modalImg.src = imageData.ruta_imagen;

        // Construir contenido del modal
        captionText.innerHTML = `
        <p class='titulo_obra_modal'>${imageData.titulo}</p>
        <p class='info_obra_modal'>MANUEL AZCUY
        Technique : ${imageData.tecnica || ''}  
        Dimensions : ${imageData.medidas || ''}  
        Year : ${imageData.ano === 0 ? $imageData.ano : '' || ''}  
        Sold : ${imageData.vendido === '1' ? 'Sí' : 'No'} 
        Exhibited : ${imageData.expuesto || ''} 
        Series : ${imageData.serie || ''}
    </p>
    `;
    }

    // Delegación de eventos para abrir el modal al hacer clic en las imágenes en `#galleryContainer`
    document.getElementById("galleryContainer").addEventListener("click", function (event) {

        if (event.target.tagName === "IMG") { // Verifica si el elemento clicado es una imagen

            // Construir el objeto imageData con los datos de la imagen clickeada
            const imageData = {
                ruta_imagen: event.target.src,
                titulo: event.target.getAttribute("data-titulo"),
                tecnica: event.target.getAttribute("data-tecnica"),
                medidas: event.target.getAttribute("data-medidas"),
                ano: event.target.getAttribute("data-ano"),
                vendido: event.target.getAttribute("data-vendido"),
                expuesto: event.target.getAttribute("data-expuesto"),
                serie: capitalizeWords(event.target.getAttribute("data-serie"))
            };

            // Desactiva el scroll de la página
            document.body.classList.add("modal-open"); 
            document.documentElement.classList.add('modal-open');
            
            openModal(imageData); // Llama a la función con los datos de la imagen
        }
    });

    window.onclick = function (event) {
        if (event.target == modal) {
            closeModal();
        }
    };

    // Cargar imágenes y paginación al iniciar la página
    
    if (serie) {
        cargarImagenes(serie, currentPage);
        cargarPaginacion(serie, currentPage);
    } else {
        console.error('No se encontró la serie .');
    }
});

// Función para cerrar el modal
function closeModal() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
    // Restaura el scroll
    document.body.classList.remove("modal-open"); 
    document.documentElement.classList.remove('modal-open');
}

// Función para capitalizar la primera letra de cada palabra
function capitalizeWords(serie) {
    return serie
        .toLowerCase() // Asegura que todo esté en minúsculas
        .split(' ')    // Divide la cadena en palabras
        .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Capitaliza la primera letra
        .join(' ');    // Une las palabras de nuevo
}