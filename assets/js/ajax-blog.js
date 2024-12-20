// cargar imagenes y modal
$(document).ready(function () {

    let currentPage = 1; // Página inicial

 // Cargar archives blogs usando AJAX
 function cargarArchiveBlogs(page = 1) {
    $.ajax({
        url: 'assets/config/blog_carga_dinamica_archive_section.php', // Archivo PHP que devuelve el HTML de los blogs
        method: 'GET',
        data: { page: page }, // Pasar el número de página a la solicitud
        success: function (data) {
            // cargar los blogs desde la bd 
            $('#archives').html(data);
            currentPage = page; // Actualizar la página actual
        },
        error: function () {
            $('#archives').html('<p>Error al cargar los Blogs.</p>');
        }
    });

}

    // Cargar los recent 5 blogs usando AJAX
    function cargarRecentBlogs(page = 1) {
        $.ajax({
            url: 'assets/config/blog_carga_recent_post.php', // Archivo PHP que devuelve el HTML de los blogs
            method: 'GET',
            data: { page: page }, // Pasar el número de página a la solicitud
            success: function (data) {
                // cargar los blogs desde la bd 
                $('#last5-post').html(data);
                currentPage = page; // Actualizar la página actual
            },
            error: function () {
                $('#last5-post').html('<p>Error al cargar los Blogs.</p>');
            }
        });

    }

    


    // Cargar los blogs usando AJAX
    function cargarBlogs(page = 1) {
        $.ajax({
            url: 'assets/config/blog_carga_dinamica.php', // Archivo PHP que devuelve el HTML de los blogs
            method: 'GET',
            data: { page: page }, // Pasar el número de página a la solicitud
            success: function (data) {
                // cargar los blogs desde la bd 
                $('#blogContainer').html(data);
                // Utilizamos imagesLoaded para asegurarnos de que las imágenes hayan cargado antes de inicializar Isotope
                $('#blogContainer').imagesLoaded().done(function () {
                    // Inicializar o re-inicializar Isotope después de que las imágenes se hayan cargado completamente
                    // var $grid = $('#galleryContainer').isotope({
                    //     itemSelector: '.pitem',
                    //     layoutMode: 'fitRows'
                    // });

                    // Si se accede con un parámetro 'category', activar el filtro
                    // if (initialCategory) {
                    //     const categoryMap = {
                    //         escultura: ".escultura",
                    //         figurativa: ".pintura_figurativa",
                    //         abstracta: ".pintura_abstracta"
                    //     };

                    //     const filter = categoryMap[initialCategory];
                    //     if (filter) {
                    //         console.log("Aplicando filtro inicial para la categoría:", filter);
                    //         $grid.isotope({ filter: filter });

                    //         // Activar el botón correspondiente
                    //         $(".portfolio_area li").removeClass("current_menu_item");
                    //         $(`.portfolio_area li[data-filter="${filter}"]`).addClass("current_menu_item");
                    //     }
                    // } else {
                    //     console.log("Sin categoría inicial. Mostrando todas las imágenes.");
                    // }

                    // $grid.isotope('reloadItems').isotope();
                });

                currentPage = page; // Actualizar la página actual
            },
            error: function () {
                $('#blogContainer').html('<p>Error al cargar los Blogs.</p>');
            }
        });
    }

    // Función para cargar la paginación
    function cargarPaginacion(page = 1) {
        $.ajax({
            url: 'assets/config/blog_cargar_paginacion.php',
            method: 'GET',
            data: { page: page },
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
            cargarBlogs(page);
            cargarPaginacion(page);
            cargarRecentBlogs(page);
            cargarArchiveBlogs(page)
        }
    });

    // detectar clics en el menú y mostrar/ocultar dinámicamente los blogs según el filtro seleccionado
    $(document).ready(function () {
        // Delegación de eventos para manejar elementos dinámicos
        $(document).on("click", ".inner_yblog_left_title ul li", function () {
            // Obtener el filtro seleccionado
            console.log('click en archivo ')
            var filter = $(this).attr("data-filter");
    
            // Agregar clase activa al menú seleccionado
            $(".inner_yblog_left_title ul li").removeClass("current_menu_item");
            $(this).addClass("current_menu_item");
    
            // Mostrar/Ocultar blogs según el filtro
            if (filter === "*") {
                $(".blog_item").show(); // Mostrar todos
            } else {
                $(".blog_item").hide(); // Ocultar todos
                $(filter).show(); // Mostrar los filtrados
            }
        });
    });

   
    
    
    

    // Llamada inicial para cargar las imágenes
    cargarBlogs(currentPage);
    cargarPaginacion(currentPage);
    cargarRecentBlogs(currentPage);
    cargarArchiveBlogs(currentPage);
});


