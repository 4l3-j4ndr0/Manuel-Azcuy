<?php
// Habilitar el reporte de errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Asegurarse de que no haya salida antes del encabezado
header('Content-Type: application/json');

// Validar el método de la solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    exit();
}

// Capturar el contenido de la solicitud
$data = json_decode(file_get_contents('php://input'), true);
$url = isset($data['url']) ? $data['url'] : null;

if (!$url) {
    echo json_encode(['status' => 'error', 'message' => 'La URL es obligatoria']);
    exit();
}

// Función para obtener contenido usando cURL
function get_url_content($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Seguir redirecciones
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Tiempo de espera
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignorar verificación del certificado
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Ignorar verificación del host
    $output = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($output === false) {
        throw new Exception("Error al obtener contenido: $error");
    }

    return $output;
}


try {
    // Obtener contenido de la URL
    $content = get_url_content($url);

    // Extraer título (<h1>)
    preg_match('/<h1[^>]*>(.*?)<\/h1>/si', $content, $titleMatches);

    // Mejor lógica para extraer el extracto (<p>)
    // Buscar el primer <p> relevante dentro de contenedores típicos de contenido
    preg_match('/<main[^>]*>.*?<p[^>]*>(.*?)<\/p>/si', $content, $extractMatches);
    if (empty($extractMatches)) {
        preg_match('/<article[^>]*>.*?<p[^>]*>(.*?)<\/p>/si', $content, $extractMatches);
    }
    if (empty($extractMatches)) {
        preg_match('/<div[^>]*(class=["\'].*?(content|main-content|body|texto).*?["\']).*?>.*?<p[^>]*>(.*?)<\/p>/si', $content, $extractMatches);
    }

    $titulo = isset($titleMatches[1]) ? $titleMatches[1] : null;
    $extracto = isset($extractMatches[1]) ? substr(strip_tags($extractMatches[1]), 0, 213) : null;

    // Si no se pudo extraer ningún dato
    if (!$titulo && !$extracto) {
        $titulo='No se pudo extrar el texto';
        $extracto='No se pudo extrar el texto';
    }

    

    // Respuesta exitosa
    echo json_encode([
        'status' => 'success',
        'titulo' => $titulo,
        'extracto' => $extracto,
    ]);
} catch (Exception $e) {
    // Capturar errores y devolverlos como JSON
    echo json_encode([
        'status' => 'error',
        'message' => 'Error: ' . $e->getMessage(),
    ]);
    exit();
}

