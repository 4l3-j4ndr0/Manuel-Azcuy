<?php
// Conexión a la base de datos
require_once '../../login/config/conexion.php';

// Función para cargar imágenes relevantes o de categorías alternativas
function cargarImagenesFooter()
{
    global $conexion;

    // Consulta para obtener imágenes marcadas como relevantes (máximo 6)
    $queryRelevantes = "SELECT CONCAT('login/', ruta_imagen) AS ruta_imagen, categoria, subcategoria FROM imagenes WHERE relevante = 1 LIMIT 6";
    $resultRelevantes = $conexion->query($queryRelevantes);

    $imagenesPorCategoria = [
        'escultura' => [],
        'dibujo' => [],
        'figurativa' => [],
        'abstracta' => []
    ];

    while ($row = $resultRelevantes->fetch_assoc()) {
        $categoria = $row['categoria'] ?: $row['subcategoria'];
        if (isset($imagenesPorCategoria[$categoria])) {
            $imagenesPorCategoria[$categoria][] = $row['ruta_imagen'];
        }
    }

    // Si no hay suficientes imágenes relevantes, completar con otras imágenes
    foreach ($imagenesPorCategoria as $categoria => $imagenes) {
        if (count($imagenes) < 1) {
            $limite = 1 - count($imagenes);
            $subQuery = "SELECT CONCAT('login/', ruta_imagen) AS ruta_imagen FROM imagenes WHERE 
                        (categoria = '$categoria' OR subcategoria = '$categoria') LIMIT $limite";
            $resultCategoria = $conexion->query($subQuery);

            while ($row = $resultCategoria->fetch_assoc()) {
                $imagenesPorCategoria[$categoria][] = $row['ruta_imagen'];
            }
        }
    }

    // Devolver las imágenes en el orden deseado de categorías
    $categoriasOrdenadas = ['escultura', 'dibujo', 'figurativa', 'abstracta'];
    $imagenesOrdenadas = [];

    foreach ($categoriasOrdenadas as $categoria) {
        if (!empty($imagenesPorCategoria[$categoria])) {
            $imagenesOrdenadas[] = $imagenesPorCategoria[$categoria][0];
        }
    }

    return $imagenesOrdenadas;
}

// Generación del HTML de salida para las imágenes en el footer
$imagenesFooter = cargarImagenesFooter();

// Array con URLs correspondientes a las categorías en el orden deseado
$urls = [
    'escultura' => 'gallery_sculture.html',
    'dibujo' => 'gallery_drawing.html',
    'figurativa' => 'gallery_pintura_figurativa.html',
    'abstracta' => 'gallery_pintura_abstracta.html'
];

// Asignar enlaces en función del orden de las imágenes
$categoriasOrdenadas = ['escultura', 'dibujo', 'figurativa', 'abstracta'];

foreach ($imagenesFooter as $index => $rutaImagen) {
    $categoria = $categoriasOrdenadas[$index];

    echo '<div class="col-6 mb-4 rcnt-img">';
    echo "    <a href=\"{$urls[$categoria]}\">";
    echo "        <img src=\"$rutaImagen\" alt=\"artwork\" class=\"img-fluid\">";
    echo '    </a>';
    echo '</div>';
}
?>