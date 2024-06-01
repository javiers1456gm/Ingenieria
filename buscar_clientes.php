<?php
// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "autoshop");

// Verificar la conexión
if (!$conn) {
    die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
}

// Obtener el término de búsqueda y validar
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($searchTerm === '') {
    // Si el término de búsqueda está vacío, devolver un array JSON vacío
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Construir y ejecutar la consulta SQL utilizando una consulta preparada
$query = "SELECT idClientes, nombre_cliente FROM Clientes WHERE nombre_cliente LIKE ?";
$stmt = mysqli_prepare($conn, $query);
if ($stmt) {
    // Asociar el parámetro de búsqueda con la consulta preparada
    $searchTerm = "%" . mysqli_real_escape_string($conn, $searchTerm) . "%";
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);

    // Ejecutar la consulta preparada
    mysqli_stmt_execute($stmt);

    // Obtener los resultados de la consulta
    $result = mysqli_stmt_get_result($stmt);

    // Construir el array de clientes
    $clientes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $clientes[] = $row;
    }

    // Devolver los resultados en formato JSON
    header('Content-Type: application/json');
    echo json_encode($clientes);
} else {
    // Si la consulta preparada falla, devolver un mensaje de error
    die("Error en la consulta SQL: " . mysqli_error($conn));
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
