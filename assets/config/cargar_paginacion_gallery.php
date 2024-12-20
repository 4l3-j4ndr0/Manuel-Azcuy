<?php
require_once '../../login/config/conexion.php';
session_start();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$imagesPerPage = 9;
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;

// Obtener el total de imágenes
if ($categoria == 'abstracta' || $categoria == 'figurativa') {
    $countQuery = "SELECT COUNT(*) as total FROM imagenes WHERE categoria = 'pintura' and TRIM(subcategoria) = ?";
} else {
    $countQuery = "SELECT COUNT(*) as total FROM imagenes WHERE categoria = ?";
}

$stmt = $conexion->prepare($countQuery);

if ($categoria == 'abstracta' || $categoria == 'figurativa') {
    $stmt->bind_param("s", $categoria);
} else {
    $stmt->bind_param("s", $categoria);
}

if ($stmt->execute()) {
    $countResult = $stmt->get_result();
    $countRow = $countResult->fetch_assoc();
    $totalImages = $countRow['total'];
    $totalPages = ceil($totalImages / $imagesPerPage);
} else {
    echo "Error en la consulta: " . $stmt->error;
}

$stmt->close();


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
