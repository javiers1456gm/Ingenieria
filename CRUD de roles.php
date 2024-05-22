<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Roles</title>
    <!-- Bootstrap CSS v5.3.2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
            background-color: #f5f5f5;
        }

        .table-selected {
            background-color: #e9ecef !important;
        }
    </style>
    <?php require 'navBar.php'; ?>
</head>
<body>
    <div class="container mt-5">
        <h2>CRUD de Roles</h2>

<?php
// Conexión a la base de datos
$conn = new mysqli("localhost", "root", "", "autoshop");

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejar la lógica del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se está agregando, modificando o borrando un rol
    if (isset($_POST["accion"])) {
        $accion = $_POST["accion"];
        if ($accion == "agregar") {
            // Lógica para agregar un nuevo rol
            $nombre = $_POST["nombre"];
            $descripcion = $_POST["descripcion"];
            $estatus = "Activo"; // Por defecto, se agrega con estatus activo
            
            // Consulta para verificar si ya existe un rol con el mismo nombre
            $sql_check_duplicate = "SELECT idroles FROM roles WHERE nombre_rol = ?";
            $stmt_check_duplicate = $conn->prepare($sql_check_duplicate);
            $stmt_check_duplicate->bind_param("s", $nombre);
            $stmt_check_duplicate->execute();
            $result_check_duplicate = $stmt_check_duplicate->get_result();
            
            // Verificar si se encontró algún resultado
            if ($result_check_duplicate->num_rows > 0) {
                // Si ya existe un rol con el mismo nombre, mostrar un mensaje de error
                $_SESSION['alerta'] = "Ya existe un rol con el mismo nombre.";
            } else {
                // Si no existe un rol con el mismo nombre, proceder con la inserción
                $sql_insert = "INSERT INTO roles (nombre_rol, descripcion_rol, estatus_rol) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sss", $nombre, $descripcion, $estatus);
                if ($stmt_insert->execute()) {
                    $_SESSION['alerta'] = "El rol se ha agregado correctamente.";
                } else {
                    $_SESSION['alerta'] = "Error al agregar el rol: " . $conn->error;
                }
                $stmt_insert->close();
            }
            $stmt_check_duplicate->close();
        } elseif ($accion == "modificar") {
            // Lógica para modificar un rol existente
            $nombre = $_POST["nombre"];
            $descripcion = $_POST["descripcion"];

            // Realizar una consulta para obtener el ID del rol basado en el nombre proporcionado
            $sql = "SELECT idroles FROM roles WHERE nombre_rol = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $result = $stmt->get_result();

            // Verificar si se encontró un resultado
            if ($result->num_rows > 0) {
                // Obtener el ID del rol
                $row = $result->fetch_assoc();
                $id_rol = $row['idroles'];

                // Usar el ID del rol para actualizar el registro en la base de datos con los nuevos valores proporcionados en el formulario
                $sql_update = "UPDATE roles SET descripcion_rol=? WHERE idroles=?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("si", $descripcion, $id_rol);
                $stmt_update->execute();
                $stmt_update->close();
                echo "El rol con ID $id_rol se ha modificado correctamente.";
            } else {
                echo "No se encontró ningún rol con el nombre proporcionado.";
            }

            $stmt->close();
        } elseif ($accion == "borrar") {
            // Lógica para borrar un rol existente
            if (isset($_POST["rol_id"])) { // Verificar si se envió el ID del rol
                $id_rol = $_POST["rol_id"];

                // Verificar si el rol está en uso (por ejemplo, asociado a algún usuario)
                $sql_check_usage = "SELECT COUNT(*) as count FROM usuarios WHERE idroles = ?";
                $stmt_check_usage = $conn->prepare($sql_check_usage);
                $stmt_check_usage->bind_param("i", $id_rol);
                $stmt_check_usage->execute();
                $result_check_usage = $stmt_check_usage->get_result();
                $usage_count = $result_check_usage->fetch_assoc()['count'];

                if ($usage_count > 0) {
                    // Si el rol está en uso, establece un mensaje de alerta y no ejecutes la consulta de eliminación
                    $_SESSION['alerta'] = "El rol está en uso y no se puede eliminar.";
                } else {
                    // Si el rol no está en uso, procede con la eliminación
                    $sql_delete = "DELETE FROM roles WHERE idroles=?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("i", $id_rol);
                    if ($stmt_delete->execute()) {
                        $_SESSION['alerta'] = "El rol con ID $id_rol se ha borrado correctamente.";
                    } else {
                        $_SESSION['alerta'] = "Error al borrar el rol: " . $conn->error;
                    }
                    $stmt_delete->close();
                }
            } else {
                $_SESSION['alerta'] = "No se pudo borrar el rol. Falta el ID del rol.";
            }
        }
        
    }
}
?>

<form method="post">
    <?php if (isset($_GET["edit"])) : ?>
        <input type="hidden" name="edit" value="<?php echo $_GET["edit"]; ?>">
    <?php endif; ?>
    <div class="mb-3">
        <label for="nombre" class="form-label">Nombre:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo isset($_GET["edit"]) ? $result->fetch_assoc()["nombre"] : ""; ?>">
    </div>

    <div class="mb-3">
        <label for="descripcion" class="form-label">Descripción:</label>
        <input type="text" class="form-control" id="descripcion" name="descripcion" required value="<?php echo isset($_GET["edit"]) ? $result->fetch_assoc()["descripcion_rol"] : ""; ?>">
    </div>

    <div class="mb-3">
        <!-- Tabla para mostrar los roles disponibles -->
        <div class="mb-5">
            <br>
            <h3>Roles Disponibles</h3>
            <br>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Conexión a la base de datos y consulta para obtener los roles
                    $conn = new mysqli("localhost", "root", "", "autoshop");
                    if ($conn->connect_error) {
                        die("Conexión fallida: " . $conn->connect_error);
                    }

                    $sql = "SELECT idroles, nombre_rol, descripcion_rol FROM roles";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr onclick=\"rellenarCampos(" . $row['idroles'] . ")\">";
                            echo "<td>" . $row['idroles'] . "</td>";
                            echo "<td>" . $row['nombre_rol'] . "</td>";
                            echo "<td>" . $row['descripcion_rol'] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No hay roles disponibles</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Agrega el campo oculto para almacenar el ID del rol -->
    <input type="hidden" id="rol_id" name="rol_id" value="">
    <button type="submit" name="accion" value="agregar" class="btn btn-dark">Agregar Rol</button>
    <button type="submit" name="accion" value="modificar" class="btn btn-dark">Modificar Rol</button>
    <button type="submit" name="accion" value="borrar" class="btn btn-dark">Borrar Rol</button>
</form>
<script>
    function rellenarCampos(id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var rol = JSON.parse(this.responseText);
                document.getElementById("nombre").value = rol.nombre_rol;
                document.getElementById("descripcion").value = rol.descripcion_rol;
                document.getElementById("rol_id").value = id; // Establecer el valor del ID del rol
                console.log(id);
            }
        };
        xhttp.open("GET", "obtener_rol.php?id=" + id, true);
        xhttp.send();
    }
</script>

<?php
// Mostrar la alerta si está seteada
if (isset($_SESSION['alerta'])) {
    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['alerta'] . '</div>';
    unset($_SESSION['alerta']); // Limpiar la alerta después de mostrarla
}
?>

    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <!-- JavaScript para Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
