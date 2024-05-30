<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "autoshop";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("La conexión ha fallado: " . $conn->connect_error);
    }

    // Obtener los valores del formulario
    $rol = isset($_POST["rol"]) ? $_POST["rol"] : '';
    $nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
    $apellido_paterno = isset($_POST["apellido_paterno"]) ? $_POST["apellido_paterno"] : '';
    $apellido_materno = isset($_POST["apellido_materno"]) ? $_POST["apellido_materno"] : '';
    $horario_entrada = isset($_POST["horario_entrada"]) ? $_POST["horario_entrada"] : '';
    $horario_salida = isset($_POST["horario_salida"]) ? $_POST["horario_salida"] : '';
    $fecha_ingreso = isset($_POST["fecha_ingreso"]) ? $_POST["fecha_ingreso"] : '';
    $estatus_usuario = isset($_POST["estatus_usuario"]) ? $_POST["estatus_usuario"] : '';
    $contrasena = isset($_POST["contrasena"]) ? $_POST["contrasena"] : '';

    // Encriptar la contraseña
    $contrasena_encriptada = password_hash($contrasena, PASSWORD_DEFAULT);

    // Verificar si el usuario ya existe en la base de datos
    $sql_verificar = "SELECT * FROM usuarios WHERE nombre_vendedor = '$nombre' AND apellido_paterno='$apellido_paterno' AND apellido_materno='$apellido_materno'";
    $resultado_verificar = $conn->query($sql_verificar);

    if ($resultado_verificar->num_rows > 0) {
        // Si el usuario ya existe, mostrar un mensaje de alerta
        echo '<script>';
        echo 'alert("¡Advertencia! El usuario ya existe en la base de datos.");';
        echo 'window.location.href = "CRUDusuarios.php";'; // Redireccionar a la página CRUDusuarios.php después de aceptar la alerta
        echo '</script>';
    } else {
        // Manejar la subida de la foto
        $foto_nombre = $_FILES['archivo']['name'];
        $foto_temp = $_FILES['archivo']['tmp_name'];
        $foto_tipo = $_FILES['archivo']['type'];

        // Directorio donde se almacenarán las imágenes subidas
        $directorio_destino = 'imagenes/';

        // Mover el archivo subido al directorio de destino
        $ruta_foto = $directorio_destino . $foto_nombre;
        if (move_uploaded_file($foto_temp, $ruta_foto)) {
            // Insertar los datos en la base de datos
            $sql = "INSERT INTO usuarios (idroles, nombre_vendedor, apellido_paterno,contrasena, apellido_materno, horario_entrada, horario_salida, fecha_ingreso,estatus_usuario, foto) 
                    VALUES ('$rol', '$nombre', '$apellido_paterno','$contrasena_encriptada', '$apellido_materno', STR_TO_DATE('$horario_entrada','%H:%i'), STR_TO_DATE('$horario_salida','%H:%i'), '$fecha_ingreso','$estatus_usuario','$ruta_foto')";

            if ($conn->query($sql) === TRUE) {
                echo "Datos insertados correctamente";
            } else {
                echo "Error al insertar datos: " . $conn->error;
            }
        } else {
            echo "Error al subir el archivo.";
        }

        // Cerrar la conexión
        $conn->close();

        // Redireccionar a la página CRUDusuarios.php después de completar las operaciones
        echo '<script>window.location.href = "CRUDusuarios.php";</script>';
        exit(); // Salir del script para evitar que se procese más código PHP
    }
}
?>