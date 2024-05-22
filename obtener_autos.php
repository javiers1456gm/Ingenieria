<?php
// Verificar si se recibió 'dueno' en la solicitud GET
$dueno = isset($_GET['dueno']) ? $_GET['dueno'] : null;

if ($dueno !== null) {
    // Realizar la consulta SQL solo si 'dueno' está definido
    $conn = new mysqli("localhost", "root", "", "autoshop");

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Preparar la consulta SQL con parámetros para evitar la inyección de SQL
    $sql = "SELECT pagos.idpagos, autos.marca, autos.modelo, autos.precio, autos.anio
    FROM autos
    INNER JOIN articulo ON autos.idautos = articulo.idautos
    INNER JOIN ventas ON ventas.idventas = articulo.idventas
    INNER JOIN pagos ON ventas.idventas=pagos.idventas
    INNER JOIN clientes ON clientes.idClientes = ventas.idClientes
    WHERE clientes.nombre_cliente = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $dueno); // 's' indica que 'dueno' es una cadena de texto
    $stmt->execute();
    $result = $stmt->get_result();

    $autos = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $autos[] = $row;
        }
    }

    // Devolver los autos como JSON
    echo json_encode($autos);

    $stmt->close();
    $conn->close();
} else {
    // Si 'dueno' no está definido, devolver un mensaje de error como JSON
    echo json_encode(array('error' => 'El nombre del dueño no se ha proporcionado'));
}
?>