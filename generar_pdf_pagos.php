<?php
// Incluir la librería TCPDF
require_once('library/tcpdf.php');

// Función para obtener los registros de pagos para el PDF con filtros de fecha y marca
function obtenerRegistrosPagos($minDate, $maxDate, $marca) {
    // Conexión a la base de datos (debes configurar tus propias credenciales)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "autoshop";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consulta SQL base para obtener los registros de pagos
    $sql = "SELECT DISTINCT p.idpagos, p.fecha_pago, p.monto_pago, p.tipo_pago, c.nombre_cliente,v.idVentas, autos.marca, autos.modelo, autos.precio, autos.anio,
    pi.idpago_individuales, pi.fecha_pago AS fecha_pago_individual, pi.monto_pago AS monto_pago_individual
FROM pagos p
JOIN ventas v ON p.idventas = v.idventas
JOIN clientes c ON v.idclientes = c.idclientes
JOIN articulo a ON v.idVentas = a.idVentas
JOIN autos ON autos.idautos = a.idautos
LEFT JOIN pagos_individuales pi ON p.idpagos = pi.idpagos";

// Agregar filtro por marca si se proporciona
if (!empty($marca)) {
$sql .= " WHERE (autos.marca = '" . $marca . "' OR autos.marca IS NULL)";
}

// Agregar filtros de fechas si se proporcionan
if (!empty($minDate) && !empty($maxDate)) {
if (!empty($marca)) {
$sql .= " AND";
} else {
$sql .= " WHERE";
}
$sql .= " p.fecha_pago BETWEEN '" . $minDate . "' AND '" . $maxDate . "'";
}

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Array para almacenar los registros de pagos
    $registros = array();

    // Verificar si se obtuvieron resultados
    if ($result->num_rows > 0) {
        // Almacenar los resultados en el array $registros
        while ($row = $result->fetch_assoc()) {
            $registros[] = $row;
        }
    }

    // Cerrar conexión
    $conn->close();

    // Retornar los registros obtenidos
    return $registros;
}

// Obtener el rango de fechas del formulario
$minDate = $_POST['min_date'] ?? null;
$maxDate = $_POST['max_date'] ?? null;
$marca = $_POST['marca'] ?? null;

// Obtener los registros de pagos con los filtros de fecha y marca
$registros = obtenerRegistrosPagos($minDate, $maxDate, $marca);

// Verificar si hay registros antes de generar el PDF
if (!empty($registros)) {
    // Generar PDF utilizando la librería TCPDF
    // Crear nuevo objeto TCPDF
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Establecer información del documento
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Registro de Pagos');
    $pdf->SetSubject('Registro de Pagos');
    $pdf->SetKeywords('Registro, Pagos, Auto Shop');

    // Establecer márgenes
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Establecer modo de subconjunto de fuentes
    $pdf->setFontSubsetting(true);

    // Agregar una página
    $pdf->AddPage();

    // Obtener la fecha y hora actual en tu zona horaria local
    date_default_timezone_set('America/Mexico_City'); // Cambia 'America/Mexico_City' por tu zona horaria
    $fechaActual = date('Y-m-d H:i:s');

    // Contenido del PDF
    $content = '<h1>Registro de Pagos</h1>';
    $content .= '<p>Fecha de generación del reporte: ' . $fechaActual . '</p>';
    $content .= '<p>Filtros aplicados:</p>';
    $content .= '<p>Rango de fechas: ' . $minDate . ' - ' . $maxDate . '</p>';
    $content .= '<p>Marca: ' . $marca . '</p>';
    $content .= '<table border="1">';
    $content .= '<tr>';
    $content .= '<th>ID Pago</th>';
    $content .= '<th>Fecha Pago</th>';
    $content .= '<th>Monto Pago</th>';
    $content .= '<th>Tipo Pago</th>';
    $content .= '<th>Nombre Cliente</th>';
    $content .= '<th>ID Ventas</th>';
    $content .= '<th>Marca</th>';
    $content .= '<th>Modelo</th>';
    $content .= '<th>Precio</th>';
    $content .= '<th>Año</th>';
    $content .= '</tr>';

    // Agregar registros de pagos al contenido del PDF
    foreach ($registros as $registro) {
        $content .= '<tr>';
        $content .= '<td>' . $registro['idpagos'] . '</td>';
        $content .= '<td>' . $registro['fecha_pago'] . '</td>';
        $content .= '<td>' . $registro['monto_pago'] . '</td>';
        $content .= '<td>' . $registro['tipo_pago'] . '</td>';
        $content .= '<td>' . $registro['nombre_cliente'] . '</td>';
        $content .= '<td>' . $registro['idVentas'] . '</td>';
        $content .= '<td>' . $registro['marca'] . '</td>';
        $content .= '<td>' . $registro['modelo'] . '</td>';
        $content .= '<td>' . $registro['precio'] . '</td>';
        $content .= '<td>' . $registro['anio'] . '</td>';
        $content .= '</tr>';
    }

    $content .= '</table>';

    // Escribir el contenido en el PDF
    $pdf->writeHTML($content, true, false, true, false, '');

    // Cerrar y generar el PDF
    $pdf->Output('Pagos.pdf', 'D');
} else {
    echo "No se encontraron registros de pagos.";
}
?>
