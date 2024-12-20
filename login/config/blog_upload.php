<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Sesión no iniciada']);
    exit();
}
// Conexión a la base de datos
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = isset($_POST['titulo']) ? $_POST['titulo'] : null;
    $extracto = isset($_POST['extracto']) ? $_POST['extracto'] : null;
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $url = isset($_POST['url']) ? $_POST['url'] : null;
    $relevante = isset($_POST['relevante']) ? 1 : 0;
    $imagenRuta = ''; 

    $extracto .='...';


    // Validación básica
    if (!$url) {
        echo json_encode(['status' => 'error', 'message' => 'La URL es obligatoria']);
        exit();
    }

    // Subir imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $uploadDir = '../images/upload/img_blog/';
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid('blog_', true) . '.' . $ext;

        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadDir . $newFileName)) {
            $imagenRuta = $uploadDir . $newFileName;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al subir la imagen']);
            exit();
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'La imagen es obligatoria']);
        exit();
    }

    // Insertar datos en la base de datos
    try {
        $stmt = $conexion->prepare("INSERT INTO blog (titulo, extracto, ruta_imagen, fecha, url, relevante) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssi', $titulo, $extracto, $imagenRuta, $fecha, $url, $relevante);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Datos guardados correctamente']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al guardar los datos']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
}
?>