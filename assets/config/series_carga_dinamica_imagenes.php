<?php
require_once '../../login/config/conexion.php';
session_start();

// Obtiene el número de página desde la petición (por defecto es 1)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$imagesPerPage = 9; // Número de imágenes por página
$offset = ($page - 1) * $imagesPerPage; // Calcula el desplazamiento para la consulta

// Saneamiento del parámetro 'serie' recibido desde la URL
$serie = mysqli_real_escape_string($conexion, $_GET['serie']);


// Consulta SQL para obtener las imágenes de la serie especificada
$query = sprintf(
    "SELECT id, titulo, tecnica, medidas, ano, vendido, expuesto, 
            CONCAT('login/', ruta_imagen) AS ruta_imagen, serie 
     FROM imagenes WHERE LOWER(REPLACE(serie, 'á', 'a')) LIKE LOWER(REPLACE('%s', 'á', 'a')) 
     LIMIT %d OFFSET %d",
    $serie, $imagesPerPage, $offset
);
$result = mysqli_query($conexion, $query);

// Construcción del HTML para mostrar las imágenes
$imagesHTML = "";
while ($row = mysqli_fetch_assoc($result)) {
    $imagesHTML .= "
        <div class='col-lg-4 col-md-6 col-sm-12 gallery-item'>
    <div class='inner_port_area'>
        <div class='inner_port_img '>
            <img data-titulo='{$row['titulo']}'
                 data-tecnica='{$row['tecnica']}'
                 data-medidas='{$row['medidas']}'
                 data-ano='{$row['ano']}'
                 data-vendido='{$row['vendido']}'
                 data-expuesto='{$row['expuesto']}'
                 data-serie='{$row['serie']}'
                 src='{$row['ruta_imagen']}' alt='artwork'>
            <div class='port_overlay_content'>
                <h3 style='color: white;'>{$row['titulo']}</h3>
            </div>
            <div class='port_overlay'></div>
        </div>
    </div>
</div>
";
}

// Imprime el HTML generado (usado por AJAX)
echo $imagesHTML;
?>
