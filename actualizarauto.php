<?php
// Iniciar la sesión
session_start();

// Crear conexión
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los valores del formulario
    $idautos = isset($_POST["idautos"]) ? $_POST["idautos"] : '';
    $marca = isset($_POST["marca"]) ? $_POST["marca"] : '';
    $modelo = isset($_POST["modelo"]) ? $_POST["modelo"] : '';
    $precio = isset($_POST["precio"]) ? $_POST["precio"] : '';
    $anio = isset($_POST["anio"]) ? $_POST["anio"] : '';

    // Imprimir los valores en la consola del navegador
    echo "<script>";
    echo "console.log('ID Autos:', '" . $idautos . "');";
    echo "console.log('Marca:', '" . $marca . "');";
    echo "console.log('Modelo:', '" . $modelo . "');";
    echo "console.log('Precio:', '" . $precio . "');";
    echo "console.log('Año:', '" . $anio . "');";
    echo "</script>";

    // Verificar si el tipo de concesión es igual a 1 para manejar la actualización de datos adicionales
    if (isset($_POST["tipo_concesion"]) && $_POST["tipo_concesion"] == 1) {
        // Obtener los valores adicionales del formulario
        $id_dueno = isset($_POST["dueño"]) ? $_POST["dueño"] : '';
        $matricula = isset($_POST["matricula"]) ? $_POST["matricula"] : '';

        // Actualizar los datos con los atributos adicionales
        $sql = "UPDATE autos SET iddueno='$id_dueno', matricula='$matricula', marca='$marca', modelo='$modelo', precio='$precio', anio='$anio' WHERE idautos='$idautos'";
    } else {
        // Actualizar solo los datos básicos del auto
        $sql = "UPDATE autos SET marca='$marca', modelo='$modelo', precio='$precio', anio='$anio' WHERE idautos='$idautos'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Datos actualizados correctamente";
    } else {
        echo "Error al actualizar datos en la tabla autos: " . $conn->error;
    }

    // Verificar si se ha enviado una nueva foto del auto
    if (isset($_FILES['archivo']) && $_FILES['archivo']['size'] > 0) {
        // Manejar la subida de la nueva foto
        $foto_nombre = $_FILES['archivo']['name'];
        $foto_temp = $_FILES['archivo']['tmp_name'];

        // Directorio donde se almacenarán las imágenes subidas
        $directorio_destino = 'imagenes_autos/';

        // Ruta de la nueva foto
        $ruta_foto = $directorio_destino . $foto_nombre;

        // Mover el archivo subido al directorio de destino
        if (move_uploaded_file($foto_temp, $ruta_foto)) {
            // Actualizar la foto del auto en la tabla fotos_auto
            $sql_update_foto = "UPDATE fotos_auto SET foto='$ruta_foto' WHERE idautos='$idautos'";

            if ($conn->query($sql_update_foto) === TRUE) {
                echo "Foto del auto actualizada correctamente";
            } else {
                echo "Error al actualizar la foto del auto: " . $conn->error;
            }
        } else {
            echo "Error al subir el archivo de la nueva foto.";
        }
    }
}

// Cerrar la conexión
$conn->close();
?>
