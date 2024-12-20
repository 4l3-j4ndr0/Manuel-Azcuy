<?php
require_once '../../login/config/conexion.php';
session_start();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$blogsPerPage = 9;

// Obtener el total de imágenes
$countQuery = "SELECT COUNT(*) as total FROM blog";
$countResult = mysqli_query($conexion, $countQuery);
$countRow = mysqli_fetch_assoc($countResult);
$totalBlogs = $countRow['total'];
$totalPages = ceil($totalBlogs / $blogsPerPage);

// Generar HTML de la paginación
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

echo $paginacionHTML;

$conexion->close();
?>
