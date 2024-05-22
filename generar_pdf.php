<?php
// Inicio del código PHP para generar PDF

// Incluir la librería TCPDF
require_once('library/tcpdf.php');

// Función para obtener los registros de ventas desde la base de datos
function obtenerRegistrosVentas() {
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

    // Consulta SQL para obtener los registros de ventas con los pagos restantes
    $sql = "SELECT v.idventas, 
                   v.fecha_venta, 
                   v.total_venta, 
                   v.anticipo, 
                   u.nombre_vendedor, 
                   c.nombre_cliente, 
                   c.correo_electronico, 
                   c.domicilio, 
                   MAX(autos.marca) AS marca, 
                   MAX(autos.modelo) AS modelo, 
                   MAX(autos.anio) AS anio,
                   (v.numero_de_pagos - COUNT(p.idpagos) - 1) AS pagos_restantes
            FROM ventas v
            JOIN clientes c ON v.idClientes = c.idClientes
            JOIN usuarios u ON v.idUsuarios = u.idUsuarios
            JOIN articulo a ON v.idVentas = a.idVentas
            JOIN autos ON autos.idautos= a.idautos
            LEFT JOIN pagos p ON v.idventas = p.idventas
            WHERE v.fecha_venta BETWEEN '2024-01-01' AND '2024-12-01'
            GROUP BY v.idventas, 
                     v.fecha_venta, 
                     v.total_venta, 
                     v.anticipo, 
                     u.nombre_vendedor, 
                     c.nombre_cliente, 
                     c.correo_electronico, 
                     c.domicilio, 
                     v.numero_de_pagos";

    // Ejecutar la consulta
    $result = $conn->query($sql);

    // Array para almacenar los registros de ventas
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

// Obtener la fecha y hora actual en tu zona horaria local
date_default_timezone_set('America/Mexico_City'); // Cambia 'America/Mexico_City' por tu zona horaria
$fechaActual = date('Y-m-d H:i:s');

// Obtener los registros de ventas
$registros = obtenerRegistrosVentas();

// Variable para almacenar la suma de las ventas
$totalVentas = 0;

// Verificar si hay registros antes de generar el PDF
if (!empty($registros)) {
    // Generar PDF utilizando la librería TCPDF
    $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);


    // Establecer información del documento
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Admin');
    $pdf->SetTitle('Registro de Ventas');
    $pdf->SetSubject('Registro de Ventas');
    $pdf->SetKeywords('Registro, Ventas, Auto Shop');

    // Establecer márgenes
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

    // Establecer modo de subconjunto de fuentes
    $pdf->setFontSubsetting(true);

    // Agregar una página
    $pdf->AddPage();

    // Contenido del PDF
    $content = '<h1>Registro de Ventas</h1>';
    $content .= '<p>Fecha de generación del reporte: ' . $fechaActual . '</p>';
    $content .= '<table border="1">';
    $content .= '<tr>';
    $content .= '<th>ID Venta</th>';
    $content .= '<th>Fecha</th>';
    $content .= '<th>Total Venta</th>';
    $content .= '<th>Anticipo</th>';
    $content .= '<th>Pagos Restantes</th>';
    $content .= '<th>Vendedor</th>';
    $content .= '<th>Nombre Cliente</th>';
    $content .= '<th>Correo Electrónico</th>';
    $content .= '<th>Domicilio</th>';
    $content .= '<th>Marca</th>';
    $content .= '<th>Modelo</th>';
    $content .= '<th>Año</th>';
    $content .= '</tr>';

    // Agregar registros de ventas al contenido del PDF
    foreach ($registros as $registro) {
        $content .= '<tr>';
        $content .= '<td>' . $registro['idventas'] . '</td>';
        $content .= '<td>' . $registro['fecha_venta'] . '</td>';
        $content .= '<td>' . $registro['total_venta'] . '</td>';
        $content .= '<td>' . $registro['anticipo'] . '</td>';
        $content .= '<td>' . $registro['pagos_restantes'] . '</td>'; // Mostrar los pagos restantes
        $content .= '<td>' . $registro['nombre_vendedor'] . '</td>';
        $content .= '<td>' . $registro['nombre_cliente'] . '</td>';
        $content .= '<td>' . $registro['correo_electronico'] . '</td>';
        $content .= '<td>' . $registro['domicilio'] . '</td>';
        $content .= '<td>' . $registro['marca'] . '</td>';
        $content .= '<td>' . $registro['modelo'] . '</td>';
        $content .= '<td>' . $registro['anio'] . '</td>';
        $content .= '</tr>';

        // Sumar la venta al total
        $totalVentas += $registro['total_venta'];
    }

    // Agregar fila con la suma total de las ventas
    $content .= '<tr>';
    $content .= '<td colspan="2"><strong>Total Ventas:</strong></td>';
    $content .= '<td colspan="10">' . $totalVentas . '</td>';
    $content .= '</tr>';

    $content .= '</table>';

    // Escribir el contenido en el PDF
    $pdf->writeHTML($content, true, false, true, false, '');

    // Cerrar y generar el PDF
    $pdf->Output('Ventas.pdf', 'D');
} else {
    echo "No se encontraron registros de ventas.";
}
?>
