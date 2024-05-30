<style>
    .table-hover tbody tr:hover {
        cursor: pointer;
        background-color: #f5f5f5;
    }

    .table-selected {
        background-color: #e9ecef !important;
    }
</style>


<?php
// Conexión a la base de datos
$conn = mysqli_connect("localhost", "root", "", "autoshop");
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Construir la consulta SQL para buscar los pagos asociados a cualquier nombre de dueño
$sql = "SELECT DISTINCT 'Pago' AS Tipo, p.idpagos AS ID, p.fecha_pago AS Fecha, p.monto_pago AS Monto, p.tipo_pago AS TipoPago, d.nombre_dueno AS Dueño, v.idVentas AS IDVentas, autos.marca AS Marca, autos.modelo AS Modelo, autos.precio AS Precio, autos.anio AS Anio
        FROM pagos p
        JOIN ventas v ON p.idventas = v.idventas
        JOIN clientes c ON v.idclientes = c.idclientes
        JOIN articulo a ON v.idVentas = a.idVentas
        JOIN autos ON autos.idautos = a.idautos
        JOIN dueno d ON autos.iddueno = d.iddueno
        WHERE d.nombre_dueno LIKE '%" . $_POST['nombre_dueño'] . "%' 

        UNION

        SELECT DISTINCT 'Pago Individual' AS Tipo, pi.idpago_individuales AS ID, pi.fecha_pago AS Fecha, pi.monto_pago AS Monto, 'Pago Individual' AS TipoPago, d.nombre_dueno AS Dueño, v.idVentas AS IDVentas, autos.marca AS Marca, autos.modelo AS Modelo, autos.precio AS Precio, autos.anio AS Anio
        FROM pagos_individuales pi
        JOIN pagos p ON pi.idpagos = p.idpagos
        JOIN ventas v ON p.idventas = v.idventas
        JOIN clientes c ON v.idclientes = c.idclientes
        JOIN articulo a ON v.idVentas = a.idVentas
        JOIN autos ON autos.idautos = a.idautos
        JOIN dueno d ON autos.iddueno = d.iddueno
        WHERE d.nombre_dueno LIKE '%" . $_POST['nombre_dueño'] . "%'";

// Ejecutar la consulta SQL
$resultado = mysqli_query($conn, $sql);

// Verificar si se obtuvieron resultados
if (mysqli_num_rows($resultado) > 0) {
    // Mostrar los resultados en forma de tabla
    echo "<table>";
    echo "<tr><th>Tipo</th><th>ID Pagos</th><th>Fecha Pago</th><th>Monto Pago</th><th>Tipo Pago</th><th>Nombre Dueño</th><th>ID Ventas</th><th>Marca</th><th>Modelo</th><th>Precio</th><th>Año</th></tr>";
    while ($row = mysqli_fetch_assoc($resultado)) {
        echo "<tr>";
        echo "<td>" . $row["Tipo"] . "</td>";
        echo "<td>" . $row["ID"] . "</td>";
        echo "<td>" . $row["Fecha"] . "</td>";
        echo "<td>$" . $row["Monto"] . "</td>";
        echo "<td>" . $row["TipoPago"] . "</td>";
        echo "<td>" . $row["Dueño"] . "</td>";
        echo "<td>" . $row["IDVentas"] . "</td>";
        echo "<td>" . $row["Marca"] . "</td>";
        echo "<td>" . $row["Modelo"] . "</td>";
        echo "<td>" . $row["Precio"] . "</td>";
        echo "<td>" . $row["Anio"] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No se encontraron pagos asociados al nombre de dueño proporcionado.";
}

// Cerrar la conexión a la base de datos
mysqli_close($conn);
?>
