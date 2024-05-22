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
    $tipo_concesion = isset($_POST["tipo_concesion"]) ? $_POST["tipo_concesion"] : '';
    $marca = isset($_POST["marca"]) ? $_POST["marca"] : '';
    $modelo = isset($_POST["modelo"]) ? $_POST["modelo"] : '';
    $precio = isset($_POST["precio"]) ? $_POST["precio"] : '';
    $anio = isset($_POST["anio"]) ? $_POST["anio"] : '';

    if ($tipo_concesion == 1) {
        // Obtener los valores adicionales del formulario
        $id_dueno = isset($_POST["dueño"]) ? $_POST["dueño"] : '';
        $matricula = isset($_POST["matricula"]) ? $_POST["matricula"] : '';

        // Verificar si la matrícula ya está registrada
        $sql_verificar_matricula = "SELECT idautos FROM autos WHERE matricula = '$matricula'";
        $result_verificar_matricula = $conn->query($sql_verificar_matricula);

        if ($result_verificar_matricula->num_rows > 0) {
            echo "<script>alert('La matrícula ingresada ya está registrada en la base de datos.');</script>";
        } else {
            $sql = "INSERT INTO autos (idtipoconcesion, iddueno, matricula, marca, modelo, precio, anio) 
                    VALUES ('$tipo_concesion', '$id_dueno', '$matricula', '$marca', '$modelo', '$precio', '$anio')";

            if ($conn->query($sql) === TRUE) {
                $id_auto = $conn->insert_id;

                foreach ($_FILES['archivos']['tmp_name'] as $key => $tmp_name) {
                    $foto_nombre = $_FILES['archivos']['name'][$key];
                    $foto_temp = $_FILES['archivos']['tmp_name'][$key];
                    $directorio_destino = 'imagenes_autos/';
                    $ruta_foto = $directorio_destino . uniqid() . '_' . $foto_nombre;

                    if (move_uploaded_file($foto_temp, $ruta_foto)) {
                        $sql_foto = "INSERT INTO fotos_auto (idautos, foto) VALUES ('$id_auto', '$ruta_foto')";
                        if ($conn->query($sql_foto) !== TRUE) {
                            echo "Error al insertar datos en la tabla fotos_auto: " . $conn->error;
                        }
                    } else {
                        echo "Error al subir el archivo.";
                    }
                }

                header("Location: CRUD_Registroautos4.php");
                exit;
            } else {
                echo "Error al insertar datos en la tabla autos: " . $conn->error;
            }
        }
    } else {
        $sql = "INSERT INTO autos (idtipoconcesion, marca, modelo, precio, anio) 
                VALUES ('$tipo_concesion', '$marca', '$modelo', '$precio', '$anio')";

        if ($conn->query($sql) === TRUE) {
            $id_auto = $conn->insert_id;

            foreach ($_FILES['archivos']['tmp_name'] as $key => $tmp_name) {
                $foto_nombre = $_FILES['archivos']['name'][$key];
                $foto_temp = $_FILES['archivos']['tmp_name'][$key];
                $directorio_destino = 'imagenes_autos/';
                $ruta_foto = $directorio_destino . uniqid() . '_' . $foto_nombre;

                if (move_uploaded_file($foto_temp, $ruta_foto)) {
                    $sql_foto = "INSERT INTO fotos_auto (idautos, foto) VALUES ('$id_auto', '$ruta_foto')";
                    if ($conn->query($sql_foto) !== TRUE) {
                        echo "Error al insertar datos en la tabla fotos_auto: " . $conn->error;
                    }
                } else {
                    echo "Error al subir el archivo.";
                }
            }

            header("Location: CRUD_Registroautos4.php");
            exit;
        } else {
            echo "Error al insertar datos en la tabla autos: " . $conn->error;
        }
    }
}

if (isset($_SESSION['matricula'])) {
    $matricula = $_SESSION['matricula'];
    $sql_id_auto = "SELECT idautos FROM autos WHERE matricula = '$matricula'";
    $result_id_auto = $conn->query($sql_id_auto);

    if ($result_id_auto->num_rows > 0) {
        $row_id_auto = $result_id_auto->fetch_assoc();
        $id_auto = $row_id_auto["idautos"];

        foreach ($_FILES['archivos']['tmp_name'] as $key => $tmp_name) {
            $foto_nombre = $_FILES['archivos']['name'][$key];
            $foto_temp = $_FILES['archivos']['tmp_name'][$key];
            $directorio_destino = 'imagenes_autos/';
            $ruta_foto = $directorio_destino . uniqid() . '_' . $foto_nombre;

            if (move_uploaded_file($foto_temp, $ruta_foto)) {
                $sql_foto = "INSERT INTO fotos_auto (idautos, foto) VALUES ('$id_auto', '$ruta_foto')";
                if ($conn->query($sql_foto) !== TRUE) {
                    echo "Error al insertar datos en la tabla fotos_auto: " . $conn->error;
                }
            } else {
                echo "Error al subir el archivo.";
            }
        }

        header("Location: CRUD_Registroautos4.php");
        exit;
    } else {
        echo "No se encontró ningún auto con la matrícula especificada.";
    }

    unset($_SESSION['matricula']);
}

$conn->close();
?>


