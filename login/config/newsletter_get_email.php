<?php
require_once 'conexion.php'; // Archivo de conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'exportNewsletter') {


    if (!$conexion) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error al conectar a la base de datos.']);
        exit();
    }

    // Consulta para obtener datos de la tabla newsletter
    $query = "SELECT email, ip_address, subscription_date, privacy_version, consent_purpose FROM newsletter";
    $result = $conexion->query($query);

    if ($result->num_rows > 0) {
        // Encabezados HTTP para el archivo Excel
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="newsletter_data_' . time() . '.xml"');


        // Generar el contenido del archivo Excel
        echo "<?xml version=\"1.0\"?>\n";
        echo "<?mso-application progid=\"Excel.Sheet\"?>\n";
        echo "<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"\n";
        echo " xmlns:o=\"urn:schemas-microsoft-com:office:office\"\n";
        echo " xmlns:x=\"urn:schemas-microsoft-com:office:excel\"\n";
        echo " xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\">\n";
        echo " <Worksheet ss:Name=\"Newsletter\">\n";
        echo "  <Table>\n";

        // Escribir encabezados personalizados
        echo "   <Row>\n";
        echo "    <Cell><Data ss:Type=\"String\">Email</Data></Cell>\n";
        echo "    <Cell><Data ss:Type=\"String\">IP Address</Data></Cell>\n";
        echo "    <Cell><Data ss:Type=\"String\">Subscription Date</Data></Cell>\n";
        echo "    <Cell><Data ss:Type=\"String\">Privacy Version</Data></Cell>\n";
        echo "    <Cell><Data ss:Type=\"String\">Consent Purpose</Data></Cell>\n";
        echo "   </Row>\n";

        // Escribir los datos de las filas
        while ($row = $result->fetch_assoc()) {
            echo "   <Row>\n";
            echo "    <Cell><Data ss:Type=\"String\">{$row['email']}</Data></Cell>\n";
            echo "    <Cell><Data ss:Type=\"String\">{$row['ip_address']}</Data></Cell>\n";
            echo "    <Cell><Data ss:Type=\"String\">{$row['subscription_date']}</Data></Cell>\n";
            echo "    <Cell><Data ss:Type=\"String\">{$row['privacy_version']}</Data></Cell>\n";
            echo "    <Cell><Data ss:Type=\"String\">{$row['consent_purpose']}</Data></Cell>\n";
            echo "   </Row>\n";
        }

        // Finalizar el archivo Excel
        echo "  </Table>\n";
        echo " </Worksheet>\n";
        echo "</Workbook>\n";
        exit();
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'No hay datos en la tabla newsletter.']);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Solicitud inválida.']);
    exit();
}
?>