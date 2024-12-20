<?php
require_once '../../login/config/conexion.php';
session_start();

// Primera consulta: seleccionar blogs relevantes
$query = "SELECT titulo, extracto, REPLACE(ruta_imagen, '../', 'login/') AS ruta_imagen, fecha, url
          FROM blog 
          WHERE relevante = 1 
          LIMIT 3";
$result = $conexion->query($query);

$blogs = []; // Almacenar los resultados

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}

// Si hay menos de 3 blogs relevantes, completar con los más recientes
if (count($blogs) < 3) {
    $faltantes = 3 - count($blogs);

    if (!empty($blogs)) {
        // Crear filtro para excluir blogs ya seleccionados
        $ids_existentes = array_map(function($blog) use ($conexion) {
            return "'" . $conexion->real_escape_string($blog['titulo']) . "'";
        }, $blogs);
        $ids_filtro = implode(',', $ids_existentes);

        $query_recentes = "
            SELECT titulo, extracto, REPLACE(ruta_imagen, '../', 'login/') AS ruta_imagen, fecha, url
            FROM blog 
            WHERE titulo NOT IN ($ids_filtro)
            ORDER BY fecha DESC
            LIMIT $faltantes";
    } else {
        // Sin blogs existentes, obtener los más recientes sin filtro
        $query_recentes = "
            SELECT titulo, extracto, REPLACE(ruta_imagen, '../', 'login/') AS ruta_imagen, fecha, url
            FROM blog
            ORDER BY fecha DESC
            LIMIT $faltantes";
    }

    // Ejecutar la consulta
    $result_recientes = $conexion->query($query_recentes);

    if ($result_recientes && $result_recientes->num_rows > 0) {
        while ($row = $result_recientes->fetch_assoc()) {
            $blogs[] = $row;
        }
    }
}


// Mostrar los resultados finales
foreach ($blogs as $row) {
    // Crear la clase dinámica basada en la fecha
    $clase_categoria = $row['fecha'];

    // TRATAMIENTO DE FECHA 
    // Crear un objeto DateTime
    $date = new DateTime($row['fecha']);

    // Convertir la fecha a una clase CSS
    $fecha_clase = strtolower(date('F-Y', strtotime($row['fecha']))); // Ejemplo: "january-2024"

    // Formatear la fecha al formato deseado: Thu 01, 2024
    $formateada = $date->format('D d, Y');

    // Generar el HTML
    echo "
        <div class='col-lg-4 col-md-6 col-sm-12'>
            <div class='inner_blog_area'>
                <!-- inner blog thumb -->
                <div class='inner_blog_thumb' >
                    <div class='inner_blog_img centered_image'>
                    <a href='{$row['url']}'>
                        <img src='{$row['ruta_imagen']}' alt='blog-{$row['titulo']}'>
                        <!-- blog overlay -->
                    <div class='blog_overlay'></div>
                        </a>
                    </div>
                </div>
                <!-- inner blog text -->
                <div class='inner_blog_text'>
                    <span><i class='icofont-calendar'></i>{$formateada} </span>
                </div>
                <!-- inner blog content -->
                <div class='inner_blog_content'>
                    <h2><a href='{$row['url']}'>{$row['titulo']}</a></h2>
                    <p>{$row['extracto']}</p>
                </div>
            </div>
        </div>
    ";
}

$conexion->close();
?>
