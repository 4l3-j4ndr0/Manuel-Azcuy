<?php
// Incluir la conexión a la base de datos
require_once '../../login/config/conexion.php';

// Inicializar un array para las imágenes
$imagenes = [];

// Ruta de la imagen por defecto
$imagen_por_defecto = 'assets\images\imagen_defoult.png'; // Cambia esta ruta a la ubicación de tu imagen por defecto

// Consulta principal: imágenes con portfolio_principal = 1
$query_principal = "SELECT CONCAT('login/', ruta_imagen) AS ruta_imagen, categoria, subcategoria FROM imagenes WHERE portfolio_principal = 1 LIMIT 3";
$result = mysqli_query($conexion, $query_principal);

while ($row = mysqli_fetch_assoc($result)) {
    $imagenes[] = $row;
}

// Completar categorías faltantes si no hay suficientes resultados
$categorias_requeridas = ['escultura', 'dibujo','figurativa', 'abstracta'];
foreach ($categorias_requeridas as $categoria) {
    // Verificar si ya existe una imagen para esta categoría
    $existe = false;
    foreach ($imagenes as $img) {
        if ($img['categoria'] === 'escultura' && $categoria === 'escultura') {
            $existe = true;
        }elseif ($img['categoria'] === 'dibujo' && $categoria === 'dibujo') {
            $existe = true;
        } elseif ($img['categoria'] === 'pintura' && $img['subcategoria'] === $categoria) {
            $existe = true;
        }
    }
    // Si no existe, buscar una imagen de esta categoría
    if (!$existe) {
        if ($categoria === 'escultura') {
            $query_faltante = "SELECT CONCAT('login/', ruta_imagen) AS ruta_imagen, categoria, subcategoria FROM imagenes WHERE categoria = 'escultura' LIMIT 1";
        } 
        if($categoria === 'dibujo') {
            $query_faltante = "SELECT CONCAT('login/', ruta_imagen) AS ruta_imagen, categoria, subcategoria FROM imagenes WHERE categoria = 'dibujo' LIMIT 1";
        }
        if($categoria != 'escultura' && $categoria != 'dibujo') {
            $query_faltante = "SELECT CONCAT('login/', ruta_imagen) AS ruta_imagen, categoria, subcategoria FROM imagenes WHERE categoria = 'pintura' AND subcategoria = '$categoria' LIMIT 1";
        }
        
        $result_faltante = mysqli_query($conexion, $query_faltante);
        if ($row_faltante = mysqli_fetch_assoc($result_faltante)) {
            $imagenes[] = $row_faltante;
        } else {
            // Agregar la imagen por defecto si no se encuentra ninguna imagen en la base de datos
            $imagenes[] = [
                'ruta_imagen' => $imagen_por_defecto,
                'categoria' => $categoria,
                'subcategoria' => $categoria === 'escultura' ? null : $categoria || $categoria === 'dibujo' ? null : $categoria
            ];
        }
    }
}

// Mantener el orden: Escultura → Figurativa → Abstracta
usort($imagenes, function ($a, $b) use ($categorias_requeridas) {
    $pos_a = array_search($a['subcategoria'] ?: $a['categoria'], $categorias_requeridas);
    $pos_b = array_search($b['subcategoria'] ?: $b['categoria'], $categorias_requeridas);
    return $pos_a - $pos_b;
});

// Devolver las imágenes en formato JSON
header('Content-Type: application/json');
echo json_encode($imagenes);
?>
