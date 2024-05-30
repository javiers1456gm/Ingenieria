<?php
// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "autoshop");

// Verificar la conexión
if (!$conn) {
    die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
}

// Obtener los parámetros de búsqueda
$nombre = isset($_GET['nombre']) ? $_GET['nombre'] : '';
$correo = isset($_GET['correo_electronico']) ? $_GET['correo_electronico'] : '';

// Construir la consulta SQL
$query = "SELECT * FROM clientes WHERE 1=1";
if (!empty($nombre)) {
    $query .= " AND nombre_cliente LIKE '%" . mysqli_real_escape_string($conn, $nombre) . "%'";
}
if (!empty($correo)) {
    $query .= " AND correo_electronico LIKE '%" . mysqli_real_escape_string($conn, $correo) . "%'";
}

$result = mysqli_query($conn, $query);

$clientes = [];
while ($row = mysqli_fetch_assoc($result)) {
    $clientes[] = $row;
}

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode($clientes);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
