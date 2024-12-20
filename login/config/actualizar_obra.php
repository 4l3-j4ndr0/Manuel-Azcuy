<?php
// Configuración inicial y sesión
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    echo 'Usuario no autorizado';
    exit();
}

// Verificar si la solicitud es válida
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario y los valores originales
    $imagen = basename($_FILES["imagen"]["name"]);
    $id = intval($_POST['id']);
    $titulo = $conexion->real_escape_string($_POST["titulo"]);
    $tecnica = $conexion->real_escape_string($_POST["tecnica"]);
    $medidas = $conexion->real_escape_string($_POST["medidas"]);
    $ano = (int) $_POST["ano"];
    $vendido = isset($_POST["vendido"]) ? 1 : 0;
    $expuesto = $conexion->real_escape_string($_POST["expuesto"]);
    $categoria = $conexion->real_escape_string($_POST["categoria"]);
    $subcategoria = isset($_POST["subcategoria"]) ? $conexion->real_escape_string($_POST["subcategoria"]) : NULL;
    $serie = $conexion->real_escape_string($_POST["serie"]);
    $rutaImagen = ''; // Ruta de la nueva imagen si se sube
    $relevante = isset($_POST["relevante"]) ? 1 : 0;
    $portfolio_principal = isset($_POST["portfolio_principal"]) ? 1 : 0;

    // Valores originales enviados para la comparación
    $originalrelevante = (int) $_POST['original_relevante'];
    $originalPortfolio_principal = (int) $_POST['original_portfolio_principal'];

    // Verificar si hubo cambios
    $cambios = [];

    if (!empty($imagen)) {

        $unic_nombre_imagen = uniqid() . "_" . $imagen;
        $ruta_imagen = "../images/upload/" . $unic_nombre_imagen . "_" . $imagen;
        $rutaImage_BD = "images/upload/" . $unic_nombre_imagen . "_" . $imagen;

        // Guardar la imagen nueva en la ruta del proyecto
        if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_imagen)) {
            echo 'Error al guardar la nueva imagen.';
            exit();
        }
        // Agregar la imagen al array de cambios
        $cambios['ruta_imagen'] = $rutaImage_BD;

        //  Guardar el valor del campo input de tipo hidden con id 'original_ruta_imagen'
        $directorio_destino = "../images/upload/";
        $original_ruta_imagen = $directorio_destino . $_POST['original_ruta_imagen'];
        // Eliminar la imagen original si existe
        if (file_exists($original_ruta_imagen)) {
            if (!unlink($original_ruta_imagen)) {
                echo 'Error al eliminar la imagen original.';
                exit();
            }
        }
    }

    if ($titulo !== $_POST['original_titulo'])
        $cambios['titulo'] = $titulo;
    if ($tecnica !== $_POST['original_tecnica'])
        $cambios['tecnica'] = $tecnica;
    if ($medidas !== $_POST['original_medidas'])
        $cambios['medidas'] = $medidas;
    if ($ano !== (int) $_POST['original_ano'])
        $cambios['ano'] = $ano;
    if ($vendido !== (int) $_POST['original_vendido'])
        $cambios['vendido'] = $vendido;
    if ($expuesto !== $_POST['original_expuesto'])
        $cambios['expuesto'] = $expuesto;
    if ($categoria === 'escultura' || $categoria === 'dibujo') {
        $subcategoria = NULL;
    }
    if ($categoria !== $_POST['original_categoria'])
        $cambios['categoria'] = $categoria;
    if ($subcategoria !== $_POST['original_subcategoria'])
        $cambios['subcategoria'] = $subcategoria;
    if ($serie !== $_POST['original_serie'])
        $cambios['serie'] = $serie;
    if ($relevante !== $originalrelevante)
        $cambios['relevante'] = $relevante;
    if ($portfolio_principal !== $originalPortfolio_principal)
        $cambios['portfolio_principal'] = $portfolio_principal;





    // Ejecutar las validaciones solo si cambiaron `$relevante` o `$portfolio_principal`
    if (isset($cambios['relevante']) || isset($cambios['portfolio_principal'])) {
        // Validar relevante
        if (isset($cambios['relevante']) && $relevante === 1) {
            $queryRelevantes = "SELECT COUNT(*) as total_relevantes FROM imagenes WHERE relevante = 1";
            $resultRelevantes = $conexion->query($queryRelevantes);
            if ($resultRelevantes) {
                $row = $resultRelevantes->fetch_assoc();
                if ($row['total_relevantes'] >= 4) {
                    echo "Error: Solo se pueden tener 4 obras marcadas como relevantes.";
                    exit();
                }
            } else {
                echo "Error al verificar el número de obras relevantes: " . $conexion->error;
                exit();
            }
        }

        /// Validar portfolio_principal
        if (isset($cambios['portfolio_principal']) && $portfolio_principal === 1) {
            $queryPortfolio_principal = "
        SELECT 
            SUM(CASE WHEN portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_portfolio_principal,
            SUM(CASE WHEN categoria = 'escultura' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_escultura,
            SUM(CASE WHEN categoria = 'dibujo' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_dibujo,
            SUM(CASE WHEN subcategoria = 'figurativa' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_figurativo,
            SUM(CASE WHEN subcategoria = 'abstracta' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_abstracto
        FROM imagenes";

            $resultPortfolio_principal = $conexion->query($queryPortfolio_principal);

            if ($resultPortfolio_principal) {
                $row = $resultPortfolio_principal->fetch_assoc();

                // Verificar si el total de registros con portfolio_principal = 1 es 4 o más
                if ($row['total_portfolio_principal'] >= 4) {
                    echo "Error: Solo se pueden tener 4 obras marcadas para mostrar en el Portfolio.";
                    exit();
                }

                // Verificar que no haya más de un registro para la categoría 'escultura'
                if ($row['total_escultura'] >= 1 && $categoria == 'escultura') {
                    echo "Error: Solo se puede tener 1 obra con la categoría 'escultura' en el Portfolio.";
                    exit();
                }

                // Verificar que no haya más de un registro para la categoría 'dibujo'
                if ($row['total_dibujo'] >= 1 && $categoria == 'dibujo') {
                    echo "Error: Solo se puede tener 1 obra con la categoría 'dibujo' en el Portfolio.";
                    exit();
                }

                // Verificar que no haya más de un registro para la subcategoría 'figurativa'
                if ($row['total_figurativo'] >= 1 && $subcategoria == 'figurativa') {
                    echo "Error: Solo se puede tener 1 obra con la subcategoría 'figurativa' en el Portfolio.";
                    exit();
                }

                // Verificar que no haya más de un registro para la subcategoría 'abstracta'
                if ($row['total_abstracto'] >= 1 && $subcategoria == 'abstracta') {
                    echo "Error: Solo se puede tener 1 obra con la subcategoría 'abstracta' en el Portfolio.";
                    exit();
                }

            } else {
                echo "Error al verificar las validaciones: " . $conexion->error;
                exit();
            }
        }

    }

    // Si no hubo cambios, mostrar un mensaje
    if (empty($cambios)) {
        echo "No hubo cambios para guardar.";
        exit();
    }

    // Construir la consulta de actualización dinámicamente solo con los campos que cambiaron
    $setClause = [];
    $params = [];
    $types = '';

    foreach ($cambios as $columna => $valor) {
        $setClause[] = "$columna = ?";
        $params[] = $valor;
        $types .= ($columna === 'ano' || $columna === 'vendido') ? 'i' : 's';
    }
    $params[] = $id;
    $types .= 'i';



    // Crear la consulta SQL
    $query = "UPDATE imagenes SET " . implode(', ', $setClause) . " WHERE id = ?";
    $stmt = $conexion->prepare($query);

    // Preparar los parámetros para bind_param con call_user_func_array
    $bind_names[] = $types;
    foreach ($params as $key => $value) {
        $bind_name = 'bind' . $key;
        $$bind_name = $value;
        $bind_names[] = &$$bind_name;
    }
    // var_dump($bind_names);

    // Llamada a bind_param dinámica usando los valores de `$params`
    call_user_func_array([$stmt, 'bind_param'], $bind_names);

    // Ejecutar la consulta y verificar si se actualizó correctamente
    if ($stmt->execute()) {
        echo "success"; // Indicar que la actualización fue exitosa
    } else {
        echo "Error al actualizar la obra: " . $conexion->error;
    }

    $stmt->close();
} else {
    echo "Solicitud no válida.";
}

$conexion->close();
?>