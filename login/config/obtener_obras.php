<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Asegúrate de que esta ruta sea correcta
require_once __DIR__ . '/conexion.php';

if (!isset($conexion)) {
    echo json_encode(['error' => 'No se pudo establecer la conexión a la base de datos']);
    exit;
}

try {
    $query = "SELECT id, titulo, tecnica, medidas, ano, vendido, expuesto, categoria, subcategoria, serie, ruta_imagen , relevante, portfolio_principal FROM imagenes ORDER BY id DESC";
    $result = $conexion->query($query);

    if ($result === false) {
        throw new Exception("Error en la consulta: " . $conexion->error);
    }

    $obras = [];
    while ($row = $result->fetch_assoc()) {
        // Convertir los valores booleanos
        $row['vendido'] = $row['vendido'] ? true : false;
        $row['relevante'] = $row['relevante'] ? true : false;
        $row['portfolio_principal'] = $row['portfolio_principal'] ? true : false;
        $obras[] = $row;
    }

    echo json_encode($obras);
} catch(Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conexion->close();
?>