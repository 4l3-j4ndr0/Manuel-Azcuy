<?php
require_once '../../login/config/conexion.php';
session_start();

// Obtener el número de página y la categoría desde la petición AJAX
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : 'dibujo';
$imagesPerPage = 9;
$offset = ($page - 1) * $imagesPerPage;

// Consulta SQL dinámica
$query = "SELECT id, titulo, tecnica, medidas, ano, vendido, expuesto, categoria, subcategoria, 
                 CONCAT('login/', ruta_imagen) AS ruta_imagen, serie 
          FROM imagenes WHERE categoria = ? LIMIT ? OFFSET ?";

if ($categoria == 'abstracta' || $categoria == 'figurativa') {
    $query = "SELECT id, titulo, tecnica, medidas, ano, vendido, expuesto, categoria, subcategoria, 
                 CONCAT('login/', ruta_imagen) AS ruta_imagen, serie 
          FROM imagenes WHERE categoria = 'pintura' and subcategoria = ? LIMIT ? OFFSET ?";
}

$stmt = $conexion->prepare($query);
$stmt->bind_param("sii", $categoria, $imagesPerPage, $offset);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "
            <!-- inner port area -->
            <div class='col-lg-4 col-md-6 col-sm-12 pitem '>
                <div class='inner_port_area'>
                    <div class='inner_port_img'>
                      <img  data-titulo='{$row['titulo']}'
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
                    <div class='port_overlay' ></div>
                    </div>
                </div>
            </div>
        ";
    }
} else {
    echo "<p>No se encontraron imágenes.</p>";
}


$stmt->close();
?>