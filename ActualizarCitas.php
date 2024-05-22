<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "autoshop";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La conexión ha fallado: " . $conn->connect_error);
    }

    $descripcion = isset($_POST["descripcion"]) ? $_POST["descripcion"] : '';
    $time = isset($_POST["time"]) ? $_POST["time"] : '';
    $vendedor = isset($_POST["vendedor"]) ? $_POST["vendedor"] : '';
    $fecha = isset($_POST["fecha"]) ? date('Y-m-d', strtotime($_POST["fecha"] . ' ' . $time)) : '';
    $idCliente = isset($_POST["cliente"]) ? $_POST["cliente"] : '';

    // Verificar si se proporciona un ID de cliente válido
    if (!empty($idCliente)) {
        // Actualizar el registro existente
        $sql = "UPDATE citas 
                SET idUsuarios = '$vendedor',
                    descripcion = '$descripcion', 
                    fecha = '$fecha', 
                    hora = STR_TO_DATE('$time','%H:%i')
                WHERE idClientes = '$idCliente'";

        if ($conn->query($sql) === TRUE) {
            echo "success"; // Envía una respuesta de éxito
        } else {
            echo "Error al actualizar datos: " . $conn->error;
        }
    } else {
        // Si no se proporciona un ID de cliente válido, muestra un mensaje de error
        echo "Error: El ID de cliente no fue proporcionado.";
    }

    $conn->close();
}
?>