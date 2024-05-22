<?php
// Verificar si se ha enviado el formulario y el ID del usuario
if (isset($_POST['idUsuario'])) {
    // Conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "autoshop";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("La conexión ha fallado: " . $conn->connect_error);
    }

    // Obtener el ID del usuario y otros datos del formulario
    $idUsuario = $_POST['idUsuario'];
    $nombre = $_POST['nombre'];
    $apellido_paterno = $_POST['apellido_paterno'];
    $apellido_materno = $_POST['apellido_materno'];
    $horario_entrada = $_POST['horario_entrada'];
    $horario_salida = $_POST['horario_salida'];
    $fecha_ingreso = $_POST['fecha_ingreso'];
    $estatus_usuario = $_POST['estatus_usuario'];
    $contrasena = $_POST['contrasena'];

    // Encriptar la contraseña
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

    // Manejar la carga de la imagen
    if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $rutaImagen = 'imagenes/' . $_FILES['archivo']['name'];
        move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaImagen);
    } else {
        // Error al cargar la imagen, usar la imagen actual del usuario o mostrar un mensaje de error
        // $rutaImagen = obtenerRutaImagenActualDelUsuario(); // Obtener la ruta de la imagen actual del usuario
        $rutaImagen = ''; // O dejar la ruta vacía si no hay imagen actual
    }

    // Actualizar los datos del usuario en la base de datos
    $sql = "UPDATE usuarios SET nombre_vendedor = '$nombre', apellido_paterno = '$apellido_paterno', 
            apellido_materno = '$apellido_materno', horario_entrada = '$horario_entrada', horario_salida = '$horario_salida', 
            fecha_ingreso = '$fecha_ingreso', estatus_usuario = '$estatus_usuario', contrasena = '$contrasena_encriptada', foto = '$rutaImagen' 
            WHERE idUsuarios = $idUsuario";

    if ($conn->query($sql) === TRUE) {
        echo "Usuario actualizado correctamente";
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Redireccionar o mostrar un mensaje de error si no se proporcionó el ID del usuario
    echo "ID de usuario no proporcionado";
}
?>
