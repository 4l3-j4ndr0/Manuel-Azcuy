<?php
require_once 'conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $conexion->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $rememberMe = isset($_POST['rememberMe']);



    // Consulta segura para obtener el usuario
    $query = $conexion->prepare("SELECT * FROM usuarios WHERE username = ?");
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

       // Verificar la contraseña
        if (password_verify($password, $user['password'])) {
            $_SESSION['usuario'] = $username ;
            echo "success"; // Enviar "success" si el login es correcto
        } else {
            echo "Contraseña incorrecta."; // Mensaje de error para contraseña incorrecta
        }
    } else {
        echo "Usuario no encontrado."; // Mensaje de error si el usuario no existe
    }
}
?>
