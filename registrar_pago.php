<?php
// Establecer la conexión a la base de datos (debes reemplazar los valores con los de tu configuración)
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

// Obtener los datos del formulario
$dueno = $_POST['dueno'];
$auto = $_POST['auto'];
$pago = $_POST['pago'];
$fecha = $_POST['fecha'];
$ventaID = $_POST['ventaID']; // Agregar la recepción del ID de venta

// Preparar y ejecutar la consulta SQL para insertar el pago
$sql = "INSERT INTO pagos_individuales (idpagos, monto_pago, fecha_pago) VALUES ('$auto', '$pago', '$fecha')";

if ($conn->query($sql) === TRUE) {
    // Si el pago se registra correctamente, mostrar una alerta en JavaScript
    echo "<script>alert('Pago registrado correctamente');</script>";
} else {
    // Si hay un error al registrar el pago, mostrar una alerta con el mensaje de error en JavaScript
    echo "<script>alert('Error al registrar el pago: " . $conn->error . "');</script>";
    // Imprimir la consulta SQL para depuración
    echo "SQL: " . $sql;
}

// Cerrar conexión
$conn->close();
?>
