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

    $cliente = isset($_POST["cliente"]) ? $_POST["cliente"] : '';
    $descripcion = isset($_POST["descripcion"]) ? $_POST["descripcion"] : '';
    $time = isset($_POST["time"]) ? $_POST["time"] : '';
    $vendedor = isset($_POST["vendedor"]) ? $_POST["vendedor"] : '';
    $fecha = isset($_POST["fecha"]) ? date('Y-m-d', strtotime($_POST["fecha"])) : '';
    $idCita = isset($_POST["citaId"]) ? $_POST["citaId"] : '';

    // Validar si ya existe una cita para el vendedor en la misma hora
    $sql_validacion = "SELECT COUNT(*) as count FROM citas WHERE fecha = '$fecha' AND hora = STR_TO_DATE('$time','%H:%i') AND idUsuarios = '$vendedor'";
    $result_validacion = $conn->query($sql_validacion);

    if ($result_validacion->num_rows > 0) {
        $row_validacion = $result_validacion->fetch_assoc();
        if ($row_validacion["count"] > 0) {
            // Si ya hay una cita para ese vendedor en esa hora, mostrar alerta y salir del script
            echo "Error: Ya existe una cita para el vendedor seleccionado en la misma hora.";
            exit();
        }
    }

    if (!empty($idCita)) {
        // Actualizar el registro existente
        $sql = "UPDATE citas SET idClientes='$cliente', Descripcion='$descripcion', hora=STR_TO_DATE('$time','%H:%i'), idUsuarios='$vendedor', fecha='$fecha' WHERE idCitas=$idCita";

        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error al actualizar datos: " . $conn->error;
        }
    } else {
        // Insertar un nuevo registro
        $sql = "INSERT INTO citas (idClientes, Descripcion, hora, idUsuarios, fecha) VALUES ('$cliente', '$descripcion', STR_TO_DATE('$time','%H:%i'), '$vendedor', '$fecha')";

        if ($conn->query($sql) === TRUE) {
            echo "success";
        } else {
            echo "Error al insertar datos: " . $conn->error;
        }
    }

    $conn->close();
}
?>
