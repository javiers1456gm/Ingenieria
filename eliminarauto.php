<?php

// Verificar si se ha enviado el ID del auto del registro a eliminar y si 'delete' está definido
if (isset($_POST['idautos']) && isset($_POST['delete']) && $_POST['delete'] === 'true') {
    // Obtener el ID del auto del registro a eliminar
    $idautos = $_POST['idautos'];

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

    // Iniciar una transacción
    $conn->begin_transaction();

    // Consulta SQL para eliminar los datos de la tabla fotos_auto
    $sql_delete_fotos_auto = "DELETE FROM fotos_auto WHERE idautos = $idautos";

    // Ejecutar la consulta para eliminar los datos de la tabla fotos_auto
    if ($conn->query($sql_delete_fotos_auto) === TRUE) {
        // Consulta SQL para eliminar el registro de autos
        $sql_delete_autos = "DELETE FROM autos WHERE idautos = $idautos";

        // Ejecutar la consulta para eliminar el registro de autos
        if ($conn->query($sql_delete_autos) === TRUE) {
            // Confirmar la transacción si ambas consultas se ejecutan correctamente
            $conn->commit();
            echo "Registro de auto eliminado correctamente";

            // Redirigir a la página CRUD Registroautos después de la eliminación
            header("Location: CRUD_Registroautos4.php");
            exit(); // Asegurar que el script termine después de la redirección
        } else {
            // Revertir la transacción si hay un error al eliminar el registro de autos
            $conn->rollback();
            echo "Error al eliminar el registro de autos: " . $conn->error;
        }
    } else {
        // Revertir la transacción si hay un error al eliminar los datos de la tabla fotos_auto
        $conn->rollback();
        echo "Error al eliminar los datos de la tabla fotos_auto: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
} else {
    echo "Error: No se ha proporcionado el ID del auto del registro a eliminar o el campo 'delete' no está definido como 'true'.";
}
?>
