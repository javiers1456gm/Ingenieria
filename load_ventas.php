<?php
include('library/tcpdf.php');
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$database = "autoshop";

$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta SQL para obtener los datos de ventas
$sql = "SELECT * FROM ventas";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Array para almacenar los datos de ventas
    $ventas = array();

    // Recorrer los resultados y almacenarlos en el array
    while ($row = $result->fetch_assoc()) {
        $ventas[] = $row;
    }

    // Convertir el array a formato JSON y devolverlo
    header('Cache-Control: no-cache, must-revalidate');
    header('Content-type: application/json');
    echo json_encode($ventas);
} else {
    echo "0 resultados";
}

// Cerrar conexión
$conn->close();
?>
