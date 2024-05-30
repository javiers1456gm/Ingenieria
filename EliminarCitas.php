<?php
// Verificar si se ha enviado el nombre del registro a eliminar y si 'delete' está definido
if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['delete'])) {
    // Obtener el nombre del registro a eliminar
    $nombre = isset($_POST["name"]) ? $_POST["name"] : ''; 

    // Obtener el valor de 'delete'
    $delete = $_POST['delete'];

    if ($delete == "true") {
        // Establecer los detalles de la conexión a la base de datos
        $servername = "localhost"; // Cambia esto por tu servidor de MySQL
        $username = "root"; // Cambia esto por tu nombre de usuario de MySQL
        $password = ""; // Cambia esto por tu contraseña de MySQL
        $dbname = "autoshop"; // Cambia esto por el nombre de tu base de datos

        // Crear una conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            die("La conexión ha fallado: " . $conn->connect_error);
        }

        // Consulta SQL para eliminar el registro
        $sql = "DELETE FROM citas WHERE idClientes = (SELECT idClientes FROM clientes WHERE nombre_cliente = '$nombre')";

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
    echo "Error: No se ha proporcionado el nombre del registro a eliminar o el campo 'delete' no está definido.";
}
?>