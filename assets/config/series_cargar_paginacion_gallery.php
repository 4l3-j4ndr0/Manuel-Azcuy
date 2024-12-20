<?php
require_once '../../login/config/conexion.php';
session_start();

// Obtiene la página actual desde la URL (por defecto es 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$imagesPerPage = 9; // Número de imágenes por página

// Saneamiento del parámetro 'serie' recibido desde la URL
$serie = mysqli_real_escape_string($conexion, $_GET['serie']);

// Consulta SQL para contar el total de imágenes de la serie especificada
$countQuery = sprintf(
    "SELECT COUNT(*) as total FROM imagenes WHERE LOWER(REPLACE(serie, 'á', 'a')) LIKE LOWER(REPLACE('%s', 'á', 'a'))",
    $serie
);
$countResult = mysqli_query($conexion, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalImages = $countRow['total']; // Total de imágenes encontradas
$totalPages = ceil($totalImages / $imagesPerPage); // Total de páginas necesarias

// Generar HTML para la paginación
$paginacionHTML = "";
if ($page > 1) {
    $paginacionHTML .= "<li><a href='javascript:void(0);' data-page='" . ($page - 1) . "' class='pagination-prev'><i class='icofont-thin-left'></i></a></li>";
}
for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = $i == $page ? 'current' : '';
    $paginacionHTML .= "<li><a href='javascript:void(0);' data-page='$i' class='pagination-number $activeClass'>$i</a></li>";
}
if ($page < $totalPages) {
    $paginacionHTML .= "<li><a href='javascript:void(0);' data-page='" . ($page + 1) . "' class='pagination-next'><i class='icofont-thin-right'></i></a></li>";
}

// Imprime el HTML de la paginación (usado por AJAX)
echo $paginacionHTML;
?>
