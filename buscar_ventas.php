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

    // Preparar la consulta SQL para obtener las ventas dentro del rango de fechas
    $sql = "SELECT DISTINCT
    v.idventas, 
    v.fecha_venta, 
    v.total_venta, 
    v.anticipo,
    v.numero_de_pagos,
    u.nombre_vendedor, 
    c.nombre_cliente, 
    c.correo_electronico, 
    c.domicilio, 
    autos.marca, 
    autos.modelo, 
    autos.precio, 
    autos.anio
FROM 
    ventas v
JOIN 
    clientes c ON v.idClientes = c.idClientes
JOIN 
    usuarios u ON v.idUsuarios = u.idUsuarios
JOIN 
    articulo a ON v.idVentas = a.idVentas 
JOIN 
    autos ON autos.idautos = a.idautos
WHERE 
    STR_TO_DATE(v.fecha_venta, '%Y-%m-%d') BETWEEN '$minDate' AND '$maxDate'";


    // Ejecutar la consulta SQL
    $result = mysqli_query($conn, $sql);

    // Verificar si se obtuvieron resultados
    if (mysqli_num_rows($result) > 0) {
        // Mostrar los resultados en la tabla
        echo "<table class='table table-striped'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID Venta</th>";
        echo "<th>Fecha</th>";
        echo "<th>Total Venta</th>";
        echo "<th>Anticipo</th>";
        echo "<th>Número de Pagos</th>";
        echo "<th>Vendedor</th>";
        echo "<th>Nombre Cliente</th>";
        echo "<th>Correo Electrónico</th>";
        echo "<th>Domicilio</th>";
        echo "<th>Marca</th>";
        echo "<th>Modelo</th>";
        echo "<th>Año</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $count = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $count++;
            $bg_color = $count % 2 == 0 ? 'even' : 'odd';
            echo "<tr class='$bg_color'>";
            echo "<td>" . $row["idventas"] . "</td>";
            echo "<td>" . $row["fecha_venta"] . "</td>";
            echo "<td>$" . $row["total_venta"] . "</td>";
            echo "<td>$" . $row["anticipo"] . "</td>";
            echo "<td>" . $row["numero_de_pagos"] . "</td>";
            echo "<td>" . $row["nombre_vendedor"] . "</td>";
            echo "<td>" . $row["nombre_cliente"] . "</td>";
            echo "<td>" . $row["correo_electronico"] . "</td>";
            echo "<td>" . $row["domicilio"] . "</td>";
            echo "<td>" . $row["marca"] . "</td>";
            echo "<td>" . $row["modelo"] . "</td>";
            echo "<td>" . $row["anio"] . "</td>";
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
