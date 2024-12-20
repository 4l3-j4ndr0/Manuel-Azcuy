<?php
require_once '../../login/config/conexion.php';
session_start();

// Consulta para obtener todas las fechas
$query = "SELECT DISTINCT fecha FROM blog ORDER BY fecha DESC";
$result = $conexion->query($query);

if (!$result) {
    echo "<p>Error en la consulta: {$conexion->error}</p>";
    exit();
}

// Array para rastrear las combinaciones de mes y año ya procesadas
$mesesProcesados = [];

if ($result->num_rows > 0) {
    // Mostrar "All Blogs" una sola vez
    echo "<li class='current_menu_item' data-filter='*'>All Blogs</li>";

    while ($row = $result->fetch_assoc()) {
        // Crear un objeto DateTime a partir de la fecha
        $date = DateTime::createFromFormat('Y-m-d', $row['fecha']);

        // Si la fecha no es válida, omitirla
        if ($date === false) {
            continue;
        }

        // Obtener el mes y año como combinación única
        $mesAnio = $date->format('Y-m'); // Ejemplo: "2024-11"

        // Si esta combinación ya fue procesada, omitirla
        if (in_array($mesAnio, $mesesProcesados)) {
            continue;
        }

        // Agregar esta combinación al array de meses procesados
        $mesesProcesados[] = $mesAnio;

        // Convertir la fecha a una clase CSS dinámica
        $fecha_clase = strtolower($date->format('F-Y')); // Ejemplo: "november-2024"

        // Formatear la fecha para mostrarla como "Mes Año"
        $texto_visible = $date->format('F Y'); // Ejemplo: "November 2024"

        // Mostrar la entrada
        echo "<li data-filter='.{$fecha_clase}'>{$texto_visible}</li>";
    }
} else {
    // En caso de que no haya resultados
    echo "<p>No blogs found.</p>";
}

$conexion->close();
?>
