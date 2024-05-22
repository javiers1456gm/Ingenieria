<?php
// Verificar si se han recibido los datos del formulario
if(isset($_POST['min_date']) && isset($_POST['max_date'])) {
    // Conexión a la base de datos
    $conn = mysqli_connect("localhost", "root", "", "autoshop");
    if (!$conn) {
        die("La conexión a la base de datos falló: " . mysqli_connect_error());
    }

    // Obtener las fechas mínima y máxima del formulario
    $minDate = $_POST['min_date'];
    $maxDate = $_POST['max_date'];
    $marca = isset($_POST['marca']) ? $_POST['marca'] : null; // Obtener la marca del formulario si está definida, de lo contrario, asignar null

    // Preparar la consulta SQL para obtener los pagos y los pagos individuales dentro del rango de fechas
    $sql = "SELECT DISTINCT 'Pago' AS Tipo, p.idpagos AS ID, p.fecha_pago AS Fecha, p.monto_pago AS Monto, p.tipo_pago AS TipoPago, c.nombre_cliente AS Cliente, v.idVentas AS IDVentas, autos.marca AS Marca, autos.modelo AS Modelo, autos.precio AS Precio, autos.anio AS Anio
    FROM pagos p
    JOIN ventas v ON p.idventas = v.idventas
    JOIN clientes c ON v.idclientes = c.idclientes
    JOIN articulo a ON v.idVentas = a.idVentas
    JOIN autos ON autos.idautos = a.idautos
    WHERE p.fecha_pago BETWEEN '$minDate' AND '$maxDate'";
    
    if ($marca !== null && $marca !== '') {
        // Agregar la condición para la marca del auto si se proporciona y no está vacía
        $sql .= " AND autos.marca = '$marca'";
    }

    $sql .= " UNION ";

    $sql .= "SELECT DISTINCT 'Pago Individual' AS Tipo, pi.idpago_individuales AS ID, pi.fecha_pago AS Fecha, pi.monto_pago AS Monto, 'Pago Individual' AS TipoPago, c.nombre_cliente AS Cliente, v.idVentas AS IDVentas, autos.marca AS Marca, autos.modelo AS Modelo, autos.precio AS Precio, autos.anio AS Anio
    FROM pagos_individuales pi
    JOIN pagos p ON pi.idpagos = p.idpagos
    JOIN ventas v ON p.idventas = v.idventas
    JOIN clientes c ON v.idclientes = c.idclientes
    JOIN articulo a ON v.idVentas = a.idVentas
    JOIN autos ON autos.idautos = a.idautos
    WHERE pi.fecha_pago BETWEEN '$minDate' AND '$maxDate'";
    
    if ($marca !== null && $marca !== '') {
        // Agregar la condición para la marca del auto si se proporciona y no está vacía
        $sql .= " AND autos.marca = '$marca'";
    }

    // Ejecutar la consulta SQL
    $result = mysqli_query($conn, $sql);

    // Verificar si se obtuvieron resultados
    if (mysqli_num_rows($result) > 0) {
        // Mostrar los resultados en la tabla
        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Tipo</th>";
        echo "<th>ID Pagos</th>";
        echo "<th>Fecha Pago</th>";
        echo "<th>Monto Pago</th>";
        echo "<th>Tipo Pago</th>";
        echo "<th>Nombre Cliente</th>";
        echo "<th>ID Ventas</th>";
        echo "<th>Marca</th>";
        echo "<th>Modelo</th>";
        echo "<th>Precio</th>";
        echo "<th>Año</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $count++;
            $bg_color = $count % 2 == 0 ? 'even' : 'odd';
            echo "<tr class='$bg_color'>";
            echo "<td>" . $row["Tipo"] . "</td>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $row["Fecha"] . "</td>";
            echo "<td>$" . $row["Monto"] . "</td>";
            echo "<td>" . $row["TipoPago"] . "</td>";
            echo "<td>" . $row["Cliente"] . "</td>";
            echo "<td>" . $row["IDVentas"] . "</td>";
            echo "<td>" . $row["Marca"] . "</td>";
            echo "<td>" . $row["Modelo"] . "</td>";
            echo "<td>" . $row["Precio"] . "</td>";
            echo "<td>" . $row["Anio"] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }

    // Cerrar conexión
    mysqli_close($conn);
} else {
    // Si no se han recibido los datos del formulario, mostrar un mensaje de error
    echo '<p>Error: No se recibieron los datos del formulario.</p>';
}
?>
