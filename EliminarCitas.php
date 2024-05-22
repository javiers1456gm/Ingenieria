<?php
if (isset($_POST['cliente']) && isset($_POST['delete'])) {
    $cliente_id = $_POST['cliente']; // Obtener el ID del cliente
    $delete = $_POST['delete'];

    if ($delete == "true") {
        // Establecer los detalles de la conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "autoshop";

        // Crear una conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("La conexión ha fallado: " . $conn->connect_error);
        }

        // Consulta SQL para eliminar la cita basada en el ID del cliente
        $sql = "DELETE FROM citas WHERE idClientes = '$cliente_id'";

        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            echo "Registro eliminado correctamente";
        } else {
            echo "Error al eliminar el registro: " . $conn->error;
        }

        // Cerrar la conexión
        $conn->close();
    }
} else {
    echo "Error: No se han proporcionado todos los datos necesarios para eliminar la cita.";
}
?>
