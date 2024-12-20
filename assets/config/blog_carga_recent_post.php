<?php
require_once '../../login/config/conexion.php';
session_start();

$query = "SELECT  titulo,  url 
          FROM blog  ORDER BY fecha DESC LIMIT 5";
$result = $conexion->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {


        echo "
                            <a href='{$row['url']}'>{$row['titulo']}.</a>
                        
                        ";
    }
} else {
    // echo "No images found.";
    echo "<p>Error in query: {$conexion->error}</p>";
}



$conexion->close();
?>