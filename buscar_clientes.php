<?php
// Establecer la conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", "autoshop");

// Verificar la conexión
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Obtener el nombre del cliente proporcionado por la solicitud
$nombreCliente = $_GET['nombre'];

// Preparar la consulta SQL para buscar clientes por nombre
$sql = "SELECT idClientes, nombre_cliente,apellido_paterno,apellido_materno,correo_electronico,telefono,domicilio FROM clientes WHERE nombre_cliente LIKE '%$nombreCliente%'";
        
// Ejecutar la consulta
$resultado = mysqli_query($conn, $sql);

// Verificar si hay resultados y preparar un array para almacenar los clientes encontrados
$clientes = array();
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($row = mysqli_fetch_assoc($resultado)) {
        $clientes[] = $row;
    }
}

// Devolver los resultados como JSON
echo json_encode($clientes);

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
