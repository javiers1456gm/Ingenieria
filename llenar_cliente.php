<?php
// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $email = $_POST["email"];
    // Aquí puedes obtener los demás datos del cliente según tu formulario

    // Crear una conexión a la base de datos
    $conn = new mysqli("localhost", "root", "", "autoshop");

    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        die("La conexión a la base de datos falló: " . $conn->connect_error);
    }

    // Construir la consulta SQL para insertar los datos del cliente
    $sql = "INSERT INTO clientes (nombre, apellido, email) VALUES ('$nombre', '$apellido', '$email')";
    // Puedes agregar más campos según tu tabla de clientes

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "Los datos del cliente se insertaron correctamente.";
    } else {
        echo "Error al insertar los datos del cliente: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
