<?php

// Verificar si se ha enviado el ID del auto del registro a eliminar y si 'delete' está definido
if (isset($_POST['idautos']) && !empty($_POST['idautos']) && isset($_POST['delete'])) {
    // Obtener el ID del auto del registro a eliminar
    $idautos = $_POST['idautos'];

    // Obtener el valor de 'delete'
    $delete = $_POST['delete'];

    if ($delete == "true") {
        // Mostrar un mensaje de confirmación utilizando JavaScript
        echo '<script>';
        echo 'if(confirm("¿Estás seguro de que quieres eliminar este auto?")) {';
        echo '  window.location.href = "eliminarauto.php?idautos=' . $idautos . '&confirm=true";';
        echo '} else {';
        echo '  window.location.href = "Registrar_autos4.php";'; // Redireccionar a la página principal si se cancela la eliminación
        echo '}';
        echo '</script>';
    }
} elseif (isset($_GET['idautos']) && isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    // Obtener el ID del auto del registro a eliminar desde la URL
    $idautos = $_GET['idautos'];

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

    // Iniciar una transacción
    $conn->begin_transaction();

    // Consulta SQL para eliminar los pagos individuales relacionados
    $sql_delete_pagos_individuales = "DELETE FROM pagos_individuales WHERE idpagos IN (SELECT idpagos FROM pagos WHERE idautos = '$idautos')";

    //DELETE FROM pagos_individuales WHERE idpagos IN (SELECT idpagos FROM pagos WHERE idautos = 7);

    // Ejecutar la consulta para eliminar los pagos individuales relacionados
    if ($conn->query($sql_delete_pagos_individuales) === TRUE) {
        // Consulta SQL para eliminar el registro de pagos
        $sql_delete_pagos = "DELETE FROM pagos WHERE idautos = '$idautos'";

        // Ejecutar la consulta para eliminar el registro de pagos
        if ($conn->query($sql_delete_pagos) === TRUE) {
            // Consulta SQL para eliminar registros de la tabla articulo
            $sql_delete_articulo = "DELETE FROM articulo WHERE idautos = '$idautos'";

            // Ejecutar la consulta para eliminar registros de la tabla articulo
            if ($conn->query($sql_delete_articulo) === TRUE) {
                // Consulta SQL para eliminar el registro de autos
                $sql_delete_autos = "DELETE FROM autos WHERE idautos = '$idautos'";

                // Ejecutar la consulta para eliminar el registro de autos
                if ($conn->query($sql_delete_autos) === TRUE) {
                    // Confirmar la transacción
                    $conn->commit();
                    echo "Registro eliminado correctamente";
                } else {
                    // Revertir la transacción en caso de error
                    $conn->rollback();
                    echo "Error al eliminar el registro de autos: " . $conn->error;
                }
            } else {
                // Revertir la transacción en caso de error
                $conn->rollback();
                echo "Error al eliminar los registros de articulo: " . $conn->error;
            }
        } else {
            // Revertir la transacción en caso de error
            $conn->rollback();
            echo "Error al eliminar el registro de pagos: " . $conn->error;
        }
    } else {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo "Error al eliminar los registros de pagos individuales: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();

    // Redireccionar a la página principal después de completar las operaciones
    header("Location: CRUD Registroautos4.php");
    exit();
} else {
    echo "Error: No se ha proporcionado el ID del auto del registro a eliminar o el campo 'delete' no está definido.";
}
?>
