<?php
require_once 'conexion.php';

// Asegurarnos de que el ID sea un entero
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']); // Convertimos el ID a entero

    // Obtener la ruta de la imagen antes de eliminar
    $query = "SELECT ruta_imagen FROM imagenes WHERE id = ?";
    $stmt = $conexion->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
           
            // Eliminar la imagen del servidor
            $ruta_imagen = "../" . $row['ruta_imagen']; // Ajustar la ruta según la estructura de tu proyecto
            if (file_exists($ruta_imagen)) {
                unlink($ruta_imagen);
            }

            // Eliminar el registro de la base de datos
            $delete_query = "DELETE FROM imagenes WHERE id = ?";
            $delete_stmt = $conexion->prepare($delete_query);
            if ($delete_stmt) {
                $delete_stmt->bind_param("i", $id);
                if ($delete_stmt->execute()) {
                    echo json_encode(["status" => "success", "message" => "Imagen eliminada correctamente."]);
                    exit;
                }
            }
        }
    }

    // Si llegamos aquí, algo falló
    echo json_encode(["status" => "error", "message" => "Error al eliminar la imagen."]);
    exit;
}

echo json_encode(["status" => "error", "message" => "ID inválido o método no permitido."]);
?>
