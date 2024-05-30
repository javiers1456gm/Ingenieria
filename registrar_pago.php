<?php
// Establecer la conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "autoshop";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar un array para almacenar las alertas
$alerts = [];

// Verificar que todos los datos del formulario están presentes
if (isset($_POST['dueno']) && isset($_POST['auto']) && isset($_POST['pago']) && isset($_POST['fecha'])) {
    $dueno = $conn->real_escape_string($_POST['dueno']);
    $auto = $conn->real_escape_string($_POST['auto']);
    $pago = $conn->real_escape_string($_POST['pago']);
    $fecha = $conn->real_escape_string($_POST['fecha']);

    // Obtener el total de la venta y el anticipo del auto seleccionado
    $sqlVenta = "SELECT total_venta, anticipo FROM ventas WHERE idventas = '$auto'";
    $resultVenta = $conn->query($sqlVenta);

    if ($resultVenta) {
        if ($resultVenta->num_rows > 0) {
            $rowVenta = $resultVenta->fetch_assoc();
            $totalVenta = $rowVenta['total_venta'];
            $anticipo = $rowVenta['anticipo'];

            // Obtener el total de los pagos existentes para la venta seleccionada
            $sqlPagos = "SELECT SUM(monto_pago) as totalPagado FROM pagos WHERE idventas = '$auto'";
            $resultPagos = $conn->query($sqlPagos);

            $totalPagado = 0;
            if ($resultPagos && $resultPagos->num_rows > 0) {
                $rowPagos = $resultPagos->fetch_assoc();
                $totalPagado = $rowPagos['totalPagado'];
            }

            // Verificar si el nuevo pago excede el total de la venta menos el anticipo
            if (($totalPagado + $anticipo + $pago) > $totalVenta) {
                $alerts[] = "Error: El monto del pago excede el total de la venta";
            } else {
                // Preparar y ejecutar la consulta SQL para insertar el pago
                $sql = "INSERT INTO pagos (idventas, fecha_pago, monto_pago) VALUES ('$auto', '$fecha', '$pago')";

                if ($conn->query($sql) === TRUE) {
                    // No hay error, agregamos una alerta de éxito
                    $alerts[] = "Pago registrado correctamente";
                } else {
                    // Error al insertar en la base de datos, agregamos el mensaje de error
                    $alerts[] = "Error al registrar el pago: " . $conn->error;
                    $alerts[] = "SQL: " . $sql;
                }
            }
        } else {
            $alerts[] = "Error: No se encontró la venta correspondiente";
        }
    } else {
        $alerts[] = "Error en la consulta de ventas: " . $conn->error;
    }
} else {
    $alerts[] = "Todos los campos son requeridos";
}

// Devolver las alertas como respuesta JSON
echo json_encode($alerts);

// Cerrar conexión
$conn->close();
?>





