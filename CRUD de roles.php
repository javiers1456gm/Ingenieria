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
            border: gray;
            border: radius 1px;
        }

        .table-selected {
            background-color: #e9ecef !important;
        }

        body {
    background-image: url('fondo3.jpg'); /* Cambia 'ruta/a/tu/imagen.jpg' por la ruta de tu imagen */
    background-size: cover; /* Asegura que la imagen cubra todo el fondo */
    background-position: center; /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
    background-attachment: fixed; /* Hace que la imagen de fondo se mantenga fija al hacer scroll */
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

            // Verificar si el rol ya existe en la base de datos
            $sql_check = "SELECT * FROM roles WHERE nombre_rol = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $nombre);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                // El rol ya existe, mostrar un mensaje de error
                echo "El rol '$nombre' ya existe en la base de datos.";
            } else {
                // El rol no existe, proceder a agregarlo
                $estatus = "Activo"; // Por defecto, se agrega con estatus activo
                $sql = "INSERT INTO roles (nombre_rol, descripcion_rol, estatus_rol) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $nombre, $descripcion, $estatus);
                $stmt->execute();
                $stmt->close();
                echo "El rol '$nombre' se ha agregado correctamente.";
            }
            $stmt_check->close();
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
            if (isset($_POST["nombre"])) { // Verificar si se envió el nombre del rol
                $nombre = $_POST["nombre"];
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
        
                    // Usar el ID del rol para borrar el registro en la base de datos
                    $sql_delete = "DELETE FROM roles WHERE idroles=?";
                    $stmt_delete = $conn->prepare($sql_delete);
                    $stmt_delete->bind_param("i", $id_rol);
                    $stmt_delete->execute();
                    $stmt_delete->close();
                    echo "El rol con ID $id_rol se ha borrado correctamente.";
                } else {
                    echo "No se encontró ningún rol con el nombre proporcionado.";
                }
        
                $stmt->close();
            } else {
                echo "No se pudo borrar el rol. Falta el nombre del rol.";
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

        <!-- Agrega el campo oculto para almacenar el nombre del rol -->
        <input type="hidden" name="nombre_rol" value="<?php echo isset($_GET["edit"]) ? $_GET["edit"] : ""; ?>">
        <button type="submit" name="accion" value="agregar" class="btn btn-dark">Agregar Rol</button>
        <button type="submit" name="accion" value="modificar" class="btn btn-dark">Modificar Rol</button>
        <button type="submit" name="accion" value="borrar" class="btn btn-dark">Borrar Rol</button>
    </div>
</form>
<script>
    function rellenarCampos(id) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var rol = JSON.parse(this.responseText);
                document.getElementById("nombre").value = rol.nombre_rol;
                document.getElementById("descripcion").value = rol.descripcion_rol;
            }
        };
        xhttp.open("GET", "obtener_rol.php?id=" + id, true);
        xhttp.send();
    }
</script>


    </div>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <!-- JavaScript para Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>
