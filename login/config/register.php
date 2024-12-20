<?php
require_once 'conexion.php'; // Incluye la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    // Verificar que las contraseñas coincidan
    if ($password !== $re_password) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Comprobar si el correo electrónico ya está registrado
    $check_query = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
    $check_query->bind_param("s", $email);
    $check_query->execute();
    $result = $check_query->get_result();

    if ($result->num_rows > 0) {
        echo "El correo electrónico ya está registrado.";
        exit();
    }

    // Inserción en la base de datos
    $query = $conexion->prepare("INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)");
    $query->bind_param("sss", $username, $hashed_password, $email);

    if ($query->execute()) {
        echo "success"; // Enviar "success" si todo salió bien
    } else {
        echo "Error al crear el usuario: " . $query->error;
    }

    // Cerrar las consultas y la conexión
    $check_query->close();
    $query->close();
}

$conexion->close();
?>
