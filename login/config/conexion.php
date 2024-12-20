<?php
// Ruta: assets/config/conexion.php

// Datos de conexión
$host = "localhost";         // Host local en XAMPP
$usuario = "artMazcuy";            // Usuario de la base de datos que creaste
$password = "hesfS4U63AnU9FQs"; // Contraseña del usuario que configuraste
$database = "artMazcuy";           // Nombre de la base de datos

// Crear conexión
$conexion = new mysqli($host, $usuario, $password, $database);

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Opcional: establecer el conjunto de caracteres para evitar problemas de codificación
$conexion->set_charset("utf8");

?>
