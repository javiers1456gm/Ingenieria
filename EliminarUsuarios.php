<?php
// Verificar si se ha enviado el ID del usuario del registro a eliminar y si 'delete' está definido
if (isset($_POST['idUsuario']) && !empty($_POST['idUsuario']) && isset($_POST['delete'])) {
    // Obtener el ID del usuario del registro a eliminar
    $idUsuario = $_POST['idUsuario'];

    // Obtener el valor de 'delete'
    $delete = $_POST['delete'];

    if ($delete == "true") {
        // Mostrar un mensaje de confirmación utilizando JavaScript
        echo '<script>';
        echo 'if(confirm("¿Estás seguro de que quieres eliminar este usuario?")) {';
        echo '  window.location.href = "eliminarUsuarios.php?idUsuario=' . $idUsuario . '&confirm=true";';
        echo '} else {';
        echo '  window.location.href = "CRUDusuarios.php";'; // Redireccionar a la página CRUDusuarios.php si se cancela la eliminación
        echo '}';
        echo '</script>';
    }
} elseif (isset($_GET['idUsuario']) && isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    // Obtener el ID del usuario del registro a eliminar desde la URL
    $idUsuario = $_GET['idUsuario'];

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
    $sql = "DELETE FROM Usuarios WHERE idUsuarios = '$idUsuario'";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "Registro eliminado correctamente";
    } else {
        echo "Error al eliminar el registro: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();

    // Redireccionar a la página CRUDusuarios.php después de completar las operaciones
    header("Location: CRUDusuarios.php");
    exit();
} else {
    echo "Error: No se ha proporcionado el ID del usuario del registro a eliminar o el campo 'delete' no está definido.";
}
?>