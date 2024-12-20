<?php
require_once '../../login/config/conexion.php';
session_start();

// Obtener el número de página desde la petición (usando AJAX)
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$blogsPerPage = 6; // Número de imágenes por página
$offset = ($page - 1) * $blogsPerPage;



$query = "SELECT  titulo, extracto, REPLACE(ruta_imagen, '../', 'login/') AS ruta_imagen, fecha, url
          FROM blog LIMIT $blogsPerPage OFFSET $offset";
$result = $conexion->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        // Crear la clase dinámica basada en la fecha
        $clase_categoria = $row['fecha'];

        // TRATAMIENTO DE FECHA 
        // Crear un objeto DateTime
        $date = new DateTime($row['fecha']);

        // Convertir la fecha a una clase CSS
        $fecha_clase = strtolower(date('F-Y', strtotime($row['fecha']))); // Ejemplo: "january-2024"

        // Formatear la fecha al formato deseado: Thu 01, 2024
        $formateada = $date->format('D d, Y');
        echo "
            <!-- inner blog area 8-->
                        <div class='col-lg-6 col-md-6 col-sm-12 blog_item all-blog {$fecha_clase} blog_ajax_item'>
                            <div class='inner_blog_area yblog_left_inner'>
                                <!-- inner blog thumb -->
                                <div class='inner_blog_thumb' class='blog_item '>
                                    <div class='inner_blog_img'>
                                    <a href='{$row['url']}''>
                                        <img src='{$row['ruta_imagen']}' alt='blog-{$row['titulo']}'>
                                        </a>
                                    </div>
                                    <div class='inner_left_blog_overlay'></div>
                                </div>
                                <!-- inner blog content -->
                                <div class='inner_blog_content yblog_left_sub'>
                                    <h2><a href='{$row['url']}'>{$row['titulo']}.</a></h2>
                                    <div class='inner_blog_text yblog_left_text_sub'>
                                        <span><i class='icofont-calendar'></i>{$formateada} </span>
                                    </div>
                                    <p>{$row['extracto']}</p>
                                </div>
                            </div>
                        </div>
        ";
    }
} else {
    // echo "No images found.";
    echo "<p>Error in query: {$conexion->error}</p>";
}



$conexion->close();
?>