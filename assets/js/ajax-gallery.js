// cargar imagenes y modal
$(document).ready(function () {
    let currentPage = 1; // Página inicial
    let categoria = $('body').data('categoria'); // Asigna la categoría desde el HTML
    console.log('CATEGORIA JS = ',categoria)
    
    // Cargar las imágenes usando AJAX
    function cargarImagenes(page = 1) {
        $.ajax({
            url: 'assets/config/carga_dinamica_imagenes.php', // Archivo PHP que devuelve el HTML de las imágenes
            method: 'GET',
            data: { page: page, categoria: categoria }, // Pasar el número de página a la solicitud
            success: function (data) {
                // cargar las imagenes desde la bd 
                $('#galleryContainer').html(data);
               // Utilizamos imagesLoaded para asegurarnos de que las imágenes hayan cargado antes de inicializar Isotope
               $('#galleryContainer').imagesLoaded().done(function() {
                // Inicializar o re-inicializar Isotope después de que las imágenes se hayan cargado completamente
                var $grid = $('#galleryContainer').isotope({
                    itemSelector: '',
                    layoutMode: 'fitRows'
                });

                $grid.isotope('reloadItems').isotope();
            });

            currentPage = page; // Actualizar la página actual
        },
            error: function () {
                $('#galleryContainer').html('<p>Error al cargar las imágenes.</p>');
            }
        });
    }

    // Función para cargar la paginación
    function cargarPaginacion(page = 1) {
        $.ajax({
            url: 'assets/config/cargar_paginacion_gallery.php',
            method: 'GET',
            data: { page: page, categoria: categoria },
            success: function (data) {
                $('#paginationContainer').html(data);
            },
            error: function () {
                $('#paginationContainer').html('<p>Error al cargar la paginación.</p>');
            }
        });
    }

    // Manejar el evento de clic en la paginación usando el atributo `data-page`
    $('#paginationContainer').on('click', 'a', function (e) {
        e.preventDefault(); // Evitar la recarga de la página
        let page = $(this).data('page'); // Obtener el número de página desde `data-page`
        
        if (page) {
            cargarImagenes(page);
            cargarPaginacion(page);
        }
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
        <p class='info_obra_modal'>Manuel Azcuy
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
                serie: event.target.getAttribute("data-serie")
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

    // Llamada inicial para cargar las imágenes
    cargarImagenes(currentPage);
    cargarPaginacion(currentPage);

});

// Función para cerrar el modal
function closeModal() {
    var modal = document.getElementById("imageModal");
    modal.style.display = "none";
    // Restaura el scroll
    document.body.classList.remove("modal-open"); 
    document.documentElement.classList.remove('modal-open');
}


