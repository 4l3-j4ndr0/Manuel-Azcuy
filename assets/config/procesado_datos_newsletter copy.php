<?php
// Incluir archivo de conexión
require_once '../../login/config/conexion.php';

// Configuración de cabeceras para la API
header("Content-Type: application/json");

// Verificar si se recibieron datos
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received."]);
    exit;
}

// Obtener datos enviados desde el frontend
$email = $data['email'];
$version = $data['version'];
$purpose = $data['purpose'];

// Validar datos requeridos
if (empty($email) || empty($version) || empty($purpose)) {
    echo json_encode(["success" => false, "message" => "Incomplete data."]);
    exit;
}

// Comprobar si el email ya está registrado
$sql_check = "SELECT 1 FROM newsletter WHERE email = ? LIMIT 1";
$stmt_check = $conexion->prepare($sql_check);
if (!$stmt_check) {
    echo json_encode(["success" => false, "message" => "Error preparing the query.: " . $conexion->error]);
    exit;
}

$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "The email is already subscribed."]);
    $stmt_check->close();
    exit;
}

$stmt_check->close();

// Obtener dirección IP del usuario
$ip = file_get_contents('https://api64.ipify.org?format=json');

// Insertar datos en la base de datos
$sql = "INSERT INTO newsletter (email, ip_address, subscription_date, privacy_version, consent_purpose) 
        VALUES (?, ?, NOW(), ?, ?)";
$stmt = $conexion->prepare($sql);
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error preparing the query.: " . $conexion->error]);
    exit;
}

$stmt->bind_param("ssss", $email, $ip, $version, $purpose);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "¡Thank you for subscribing!"]);
} else {
    echo json_encode(["success" => false, "message" => "Error saving the subscription.: " . $stmt->error]);
}

$stmt->close();
$conexion->close();
?>
