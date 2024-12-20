$(document).ready(function () {
    // --- Cargar imágenes dinámicas en index.html ---
    if ($(".recent-posts").length) {
        $.getJSON("assets/config/cargar_portfolio.php", function (data) {
            console.log('json : ', data)
            const containers = [
                $(".recent-posts .single-rpost").eq(0).find(".post-thumb"),
                $(".recent-posts .single-rpost").eq(1).find(".post-thumb"),
                $(".recent-posts .single-rpost").eq(2).find(".post-thumb"),
                $(".recent-posts .single-rpost").eq(3).find(".post-thumb"),
            ];

            function limpiarRuta(ruta) {
                return ruta.replace(/^(\.\.\/)+/, '');
            }

            data.forEach(function (item, index) {
                if (index < containers.length) {
                    const rutaLimpia = limpiarRuta(item.ruta_imagen);
                    containers[index].find("img").attr("src", rutaLimpia);
                }
            });
            console.log('container : ', containers[0])
        }).fail(function () {
            console.error("Error al cargar las imágenes dinámicas.");
        });
    }
    // cargar blogs relevantes 
    $.ajax({
       
        url: 'assets/config/blog_cargar_last_blog_index.php', // Archivo PHP que devuelve el HTML de los blogs
        method: 'GET',
       
        success: function (data) {
            // cargar los blogs desde la bd 
            $('#containerRelevantBlog').html(data);
            // Forzar Bootstrap a procesar el grid
        $('#containerRelevantBlog .row').css('display', 'flex'); 
        },
        error: function () {
            $('#containerRelevantBlog').html('<p>Error al cargar los Blogs.</p>');
            console.log('error dentro blog relevante');
        }
    });
});

