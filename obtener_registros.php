<?php
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

// Obtener las fechas mínima y máxima del formulario
$minDate = $_POST['min_date'];
$maxDate = $_POST['max_date'];

// Consulta para obtener los registros de ventas dentro del rango de fechas
$sql = "SELECT * FROM ventas WHERE fecha_venta BETWEEN '$minDate' AND '$maxDate'"; // Reemplaza 'ventas' con el nombre real de tu tabla de ventas
$result = $conn->query($sql);

// Verificar si se encontraron resultados
if ($result->num_rows > 0) {
    // Crear un array para almacenar los registros
    $registros = array();

    // Recorrer los resultados y almacenarlos en el array
    while($row = $result->fetch_assoc()) {
        $registros[] = $row;
    }

    // Imprimir los registros como JSON
    echo json_encode($registros);
} else {
    echo "No se encontraron registros";
}

// Cerrar conexión
$conn->close();
?>
