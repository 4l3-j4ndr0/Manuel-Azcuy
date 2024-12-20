<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php';
session_start();

// Asegúrate de que la ruta a PHPMailer sea correcta
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';
require '../../PHPMailer-master/src/Exception.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Función para enviar el código de verificación
function enviarCodigo($email, $codigo) {
    $mail = new PHPMailer(true);

    try {
        // Configuración del servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP (Gmail en este caso)
        $mail->SMTPAuth = true;
        $mail->Username = 'al3jandro9400@gmail.com'; // Tu dirección de correo
        $mail->Password = 'oltz syth ndav etqo'; // Tu contraseña o contraseña de aplicación
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configuración del correo
        $mail->setFrom('al3jandro9400@gmail.com', 'No Reply');
        $mail->addAddress($email); // Dirección del destinatario

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code';
        $mail->Body = "<p>Your password reset code is: <strong>$codigo</strong></p>";

        $mail->send();
        return "success";
    } catch (Exception $e) {
        return "Error al enviar el correo: " . $mail->ErrorInfo;
    }
}

// Procesar acciones del POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    // Enviar código de verificación al correo
    if ($action == "send_code") {
        $email = $_POST['email'];
       // $email_check = $email; // guardar el valor de email para luego enviarlo en la comprobacion del code 
        
        // Verificar que el campo de email no esté vacío
        if (empty($email)) {
            echo "El campo de correo está vacío.";
            exit();
        }

        // Verificar si el email existe en la base de datos
        $query = $conexion->prepare("SELECT * FROM usuarios WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows == 0) {
            echo "El correo no está registrado.";
            exit();
        }

        // Generar un código de 6 dígitos y guardarlo en la base de datos
        $codigo = rand(100000, 999999);
        $updateQuery = $conexion->prepare("UPDATE usuarios SET codigo_recuperacion = ? WHERE email = ?");
        $updateQuery->bind_param("is", $codigo, $email);
        $updateQuery->execute();

        // Enviar el código al correo electrónico
        $resultadoEnvio = enviarCodigo($email, $codigo);
        echo $resultadoEnvio; // Este será "success" o el mensaje de error
        exit();
    }


    
    // Verificar el código de recuperación
    if ($action == "verify_code") {
        $email = $_POST['email'];
        $code = $_POST['code'];

        // Verificar si el código coincide con el almacenado
        $query = $conexion->prepare("SELECT * FROM usuarios WHERE email = ? AND codigo_recuperacion = ?");
        $query->bind_param("si", $email, $code);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            echo "success";
        } else {
            echo "El código es incorrecto.";
        }
        exit();
    }

    // Cambiar la contraseña del usuario
    if ($action == "change_password") {
        $email = $_POST['email'];
        $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

        // Actualizar la contraseña en la base de datos
        $updateQuery = $conexion->prepare("UPDATE usuarios SET password = ?, codigo_recuperacion = NULL WHERE email = ?");
        $updateQuery->bind_param("ss", $newPassword, $email);
        
        if ($updateQuery->execute()) {
            echo "success";
        } else {
            echo "Error al cambiar la contraseña.";
        }
        exit();
    }
}

$conexion->close();
?>