<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit();
}

// Incluir el archivo de conexión
require_once 'conexion.php';

// Verificar que la conexión esté disponible
if (!$conexion) {
    echo json_encode(['status' => 'error', 'message' => 'No se pudo conectar a la base de datos']);
    exit();
}

// Ruta base para guardar las imágenes
define('RUTA_BASE_IMAGENES', '../images/upload/img_blog/');

// Recibir los datos del formulario
$id = isset($_POST['id']) ? $_POST['id'] : null;
if (!$id) {
    echo json_encode(['status' => 'error', 'message' => 'ID no especificado']);
    exit();
}

// Función para formatear la fecha al estándar ISO
function formatearFechaISO($fecha) {
    if (!$fecha) return null;
    try {
        $date = new DateTime($fecha);
        return $date->format('Y-m-d'); // Formato ISO
    } catch (Exception $e) {
        return null;
    }
}

// Convertir "sí/no" a "1/0"
function convertirRelevante($valor) {
    $valor = strtolower(trim($valor));
    return $valor === 'No' ? 0 : 1;
}

// Campos del formulario (convertidos a los formatos correctos)
$campos = [
    'ruta_imagen' => isset($_FILES['imagen']['name']) ? $_FILES['imagen']['name'] : null,
    'url' => isset($_POST['url']) ? $_POST['url'] : null,
    'titulo' => isset($_POST['titulo']) ? $_POST['titulo'] : null,
    'extracto' => isset($_POST['extracto']) ? $_POST['extracto'] : null,
    'fecha' => isset($_POST['fecha']) ? formatearFechaISO($_POST['fecha']) : null, // Formatear a ISO
    'relevante' => isset($_POST['relevante']) ? (int)$_POST['relevante'] : 0, // Convertir a 1/0
];

// Campos originales (convertidos a los formatos correctos)
$campos_originales = [
    'ruta_imagen' => isset($_POST['current-file']) ? $_POST['current-file'] : null,
    'url' => isset($_POST['original_url']) ? $_POST['original_url'] : null,
    'titulo' => isset($_POST['original_textArea']) ? $_POST['original_textArea'] : null,
    'extracto' => isset($_POST['original_extracto']) ? $_POST['original_extracto'] : null,
    'fecha' => isset($_POST['original_fecha']) ? formatearFechaISO($_POST['original_fecha']) : null, // Formatear a ISO
    'relevante' => isset($_POST['original_relevante']) ? convertirRelevante($_POST['original_relevante']) : 0, // Convertir "sí/no" a 1/0
];



// Construir la consulta solo con los campos modificados
$set_clause = [];
$param_types = ''; // Tipos de parámetros para la consulta preparada
$param_values = []; // Valores de los parámetros

foreach ($campos as $campo => $valor) {
    // Validación personalizada para 'ruta_imagen'
if ($campo === 'ruta_imagen') {
    if (!empty($valor)) {
        // Procesar la nueva imagen con un nombre único
        $ext = pathinfo($valor, PATHINFO_EXTENSION); // Obtener la extensión de la imagen
        $nuevoNombre = uniqid('blog_', true) . '.' . $ext; // Generar un nombre único
        $nueva_ruta_imagen = RUTA_BASE_IMAGENES . $nuevoNombre;

        // Mover la imagen al directorio correspondiente
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $nueva_ruta_imagen)) {
            echo json_encode(['status' => 'error', 'message' => 'Error al guardar la nueva imagen']);
            exit();
        }

        // Eliminar la imagen anterior
        if (!empty($campos_originales['ruta_imagen']) && $campos_originales['ruta_imagen'] !== 'Ninguno') {
            $ruta_imagen_anterior = RUTA_BASE_IMAGENES . basename($campos_originales['ruta_imagen']);
            if (file_exists($ruta_imagen_anterior)) {
                unlink($ruta_imagen_anterior);
            }
        }

        // Actualizar el valor de la ruta de la imagen para la base de datos
        $set_clause[] = "$campo = ?";
        $param_types .= 's';
        $param_values[] = $nueva_ruta_imagen;
    }
}
 else {
        // Validación general para los demás campos
        if ($valor !== $campos_originales[$campo]) {
            $set_clause[] = "$campo = ?";
            $param_types .= 's'; // Asignar tipo 's' (string)
            $param_values[] = $valor;
        }
    }
}

// Si no hay cambios, no realizar actualización
if (empty($set_clause)) {
    echo json_encode(['status' => 'error', 'message' => 'No hay cambios para actualizar']);
    exit();
}

// Agregar el ID como último parámetro
$param_types .= 'i'; // Tipo 'i' (integer) para el ID
$param_values[] = $id;

// Construir la consulta SQL
$sql = "UPDATE blog SET " . implode(', ', $set_clause) . " WHERE id = ?";

$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error al preparar la consulta: ' . $conexion->error]);
    exit();
}

// Vincular los parámetros dinámicamente sin usar $this
$params = array_merge([$param_types], $param_values);
call_user_func_array([$stmt, 'bind_param'], refValues($params));

// Función auxiliar para pasar referencias
function refValues($arr) {
    if (strnatcmp(phpversion(), '5.3') >= 0) {
        $refs = [];
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }
    return $arr;
}

// Ejecutar la consulta
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Registro actualizado con éxito']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error al ejecutar la consulta: ' . $stmt->error]);
}

// Cerrar la consulta y la conexión
$stmt->close();
$conexion->close();

?>
