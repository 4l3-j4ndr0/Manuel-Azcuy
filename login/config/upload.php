<?php
// Configuración inicial y sesión
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php';
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $fileName = $_FILES['imagen']['name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        echo "Error: Solo se permiten archivos JPG y PNG.";
        exit();
    }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["imagen"])) {
    // Recibe y sanitiza los datos del formulario
    $titulo = $conexion->real_escape_string($_POST["titulo"]);
    $tecnica = $conexion->real_escape_string($_POST["tecnica"]);
    $medidas = $conexion->real_escape_string($_POST["medidas"]);
    $ano = (int)$_POST["ano"];
    $vendido = isset($_POST["vendido"]) ? 1 : 0;
    $expuesto = $conexion->real_escape_string($_POST["expuesto"]);
    $categoria = $conexion->real_escape_string($_POST["categoria"]);
    $subcategoria = isset($_POST["subcategoria"]) ? $conexion->real_escape_string($_POST["subcategoria"]) : NULL;
    $serie = $conexion->real_escape_string($_POST["serie"]);
    $relevante = isset($_POST['relevante']) ? 1 : 0;
    $portfolio_principal = isset($_POST['portfolio_principal']) ? 1 : 0;

     // Validar el número de registros relevantes si el checkbox relevante está marcado
     if ($relevante || $portfolio_principal) {
        $queryRelevantes = "SELECT COUNT(*) as total_relevantes FROM imagenes WHERE relevante = 1";
        $queryPortfolio_principal = "SELECT 
        SUM(CASE WHEN portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_portfolio_principal,
        SUM(CASE WHEN categoria = 'escultura' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_escultura,
        SUM(CASE WHEN categoria = 'dibujo' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_dibujo,
        SUM(CASE WHEN subcategoria = 'figurativa' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_figurativo,
        SUM(CASE WHEN subcategoria = 'abstracta' AND portfolio_principal = 1 THEN 1 ELSE 0 END) AS total_abstracto
    FROM imagenes";
        $resultRelevantes = $conexion->query($queryRelevantes);
        $resultPortfolio_principal = $conexion->query($queryPortfolio_principal);

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
        if ($resultPortfolio_principal) {
            $row = $resultPortfolio_principal->fetch_assoc();
            
            if ($row['total_portfolio_principal'] >= 4) {
                echo "Error: Solo se pueden tener 4 obras marcadas para mostrar en el Portfolio.";
                exit();
            }
        
            if ($row['total_escultura'] >= 1 && $categoria == 'escultura') {
                echo "Error: Solo se puede tener 1 obra con la categoría 'escultura' en el Portfolio.";
                exit();
            }

            if ($row['total_dibujo'] >= 1 && $categoria == 'dibujo') {
                echo "Error: Solo se puede tener 1 obra con la categoría 'dibujo' en el Portfolio.";
                exit();
            }
        
            if ($row['total_figurativo'] >= 1 && $subcategoria == 'figurativa') {
                echo "Error: Solo se puede tener 1 obra con la subcategoría 'figurativa' en el Portfolio.";
                exit();
            }
        
            if ($row['total_abstracto'] >= 1 && $subcategoria == 'abstracta') {
                echo "Error: Solo se puede tener 1 obra con la subcategoría 'abstracta' en el Portfolio.";
                exit();
            }
        } else {
            echo "Error al verificar las validaciones: " . $conexion->error;
            exit();
        }
    }

    // Convertir el título a mayúsculas
    $titulo = strtoupper($titulo);

    // Usar "UNKNOWN" si el título está vacío
    if (trim($titulo) === '') {
        $titulo = 'UNKNOWN';
    }

     // Validar y ajustar el formato de las medidas
     if (!preg_match('/^\d+\s*x\s*\d+\s*x?\s*\d*\s*inch$/', $medidas)) {
        // Reemplazar cualquier carácter que no sea un número o "x" con un espacio
        $medidas = preg_replace('/[^0-9x]/', ' ', $medidas);
    
        // Extraer los números usando una expresión regular
        preg_match_all('/\d+/', $medidas, $matches);
    
        // Verificar la categoría para determinar el formato
        if ($categoria === 'escultura') {
            // Si la categoría es escultura, se necesitan al menos tres números
            if (count($matches[0]) >= 3) {
                $numero1 = $matches[0][0]; // Primer número
                $numero2 = $matches[0][1]; // Segundo número
                $numero3 = $matches[0][2]; // Tercer número
    
                // Formatear a "número x número x número inch"
                $medidas = $numero1 . ' x ' . $numero2 . ' x ' . $numero3 . ' inch';
            } else {
                // Si no hay tres números, asignar un valor por defecto
                echo "Error: El campo 'medidas' debe contener al menos tres números válidos para escultura.";
                exit();
            }
        } else {
            // Si no es escultura, se necesitan al menos dos números
            if (count($matches[0]) >= 2) {
                $numero1 = $matches[0][0]; // Primer número
                $numero2 = $matches[0][1]; // Segundo número
    
                // Formatear a "número x número inch"
                $medidas = $numero1 . ' x ' . $numero2 . ' inch';
            } else {
                // Si no hay dos números, asignar un valor por defecto
                echo "Error: El campo 'medidas' debe contener al menos dos números válidos.";
                exit();
            }
        }
    }
    


    // Manejo de la subida de la imagen
    $nombreImagen = basename($_FILES["imagen"]["name"]);
    $unic_nombre_imagen = uniqid() . "_" . $nombreImagen;
    $rutaImagenGuardada = "../images/upload/" . $unic_nombre_imagen . "_" . $nombreImagen;
    $rutaImage_BD = "images/upload/" . $unic_nombre_imagen . "_" . $nombreImagen;

    if (move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaImagenGuardada)) {
        // Inserta los datos en la base de datos
        $query = "INSERT INTO imagenes (titulo, tecnica, medidas, ano, vendido, expuesto, categoria, subcategoria, serie, ruta_imagen, relevante, portfolio_principal)
                  VALUES ('$titulo', '$tecnica', '$medidas', $ano, $vendido, '$expuesto', '$categoria', '$subcategoria', '$serie', '$rutaImage_BD' ,'$relevante' ,'$portfolio_principal')";

        if ($conexion->query($query)) {
            echo "success"; // Respuesta de éxito
        } else {
            echo "Error al registrar los datos en la base de datos: " . $conexion->error;
        }
    } else {
        $fileError = $_FILES["imagen"]["error"];
        echo "Error al subir la imagen. Código de error: " . $fileError;
    }
} else {
    echo "Formulario no enviado correctamente.";
}
}
?>
