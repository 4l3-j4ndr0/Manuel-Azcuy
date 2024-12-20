<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit();
}

require_once('conexion.php'); // Ajusta la ruta según tu proyecto

header('Content-Type: application/json');

try {
    // Consultar todos los registros de la tabla `blog`
    $query = "SELECT id, titulo, extracto, ruta_imagen, fecha, relevante , url FROM blog ORDER BY fecha DESC";
    $result = $conexion->query($query);

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    echo json_encode($rows); // Devolver los datos como JSON
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al consultar los datos: ' . $e->getMessage()]);
}
?>
