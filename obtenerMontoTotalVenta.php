<?php
// Función para obtener el monto total de la venta
function obtenerMontoTotalVenta($idVenta) {
    // Realizar la conexión a la base de datos (debes reemplazar los valores de conexión con los tuyos)
    $conexion = new mysqli("localhost", "root", "", "autoshop");

    // Verificar si hay errores en la conexión
    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    // Consulta SQL para obtener el monto total de la venta con el ID proporcionado
    $sql = "SELECT total_venta FROM ventas WHERE idventas = $idVenta";

    // Ejecutar la consulta
    $resultado = $conexion->query($sql);

    // Verificar si se obtuvieron resultados
    if ($resultado->num_rows > 0) {
        // Obtener la fila de resultados
        $fila = $resultado->fetch_assoc();
        // Obtener el monto total de la venta
        $montoTotal = $fila['total_venta'];
    } else {
        // Si no se encontraron resultados, establecer el monto total en cero
        $montoTotal = 0;
    }

    // Cerrar la conexión a la base de datos
    $conexion->close();

    // Devolver el monto total de la venta
    return $montoTotal;
}
?>
