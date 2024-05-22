<?php
    session_start();
?>
<?php
require 'navBar.php';
$conn = mysqli_connect("localhost", "root", "", "autoshop");
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Bandera para mostrar o no el mensaje de completar todos los campos del formulario
$mostrarMensaje = true;

// Procesar el formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si todos los campos del formulario están presentes
    if (isset($_POST['nombre'], $_POST['apellido_paterno'], $_POST['apellido_materno'], $_POST['correo_electronico'], $_POST['telefono'], $_POST['domicilio'])) {
        // Obtener los datos del formulario
        $nombre = $_POST['nombre'];
        $apellido_paterno = $_POST['apellido_paterno'];
        $apellido_materno = $_POST['apellido_materno'];
        $correo_electronico = $_POST['correo_electronico'];
        $telefono = $_POST['telefono'];
        $domicilio = $_POST['domicilio'];

        // Preparar la consulta SQL para insertar el cliente en la tabla de clientes
        $sql = "INSERT INTO clientes (nombre_cliente, apellido_paterno_cl, apellido_materno_cl, correo_electronico, telefono, domicilio) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Vincular los parámetros
        $stmt->bind_param("ssssss", $nombre, $apellido_paterno, $apellido_materno, $correo_electronico, $telefono, $domicilio);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $mostrarMensaje = false; // No mostrar el mensaje
        } else {
            echo '<div class="alert alert-danger" role="alert">Error al registrar el cliente.</div>';
        }

        // Cerrar la declaración preparada
        $stmt->close();
    } else {
        echo '<div id="mensajeCampos" class="alert alert-danger" role="alert">Por favor complete todos los campos del formulario.</div>';
        // Script para ocultar el mensaje de campos incompletos después de 3 segundos
        echo '<script>
                setTimeout(function() {
                    document.getElementById("mensajeCampos").style.display = "none";
                }, 3000);
            </script>';
    }
}


// Función para obtener todos los clientes
function obtenerClientes($conn) {
    $sql = "SELECT * FROM clientes";
    $result = mysqli_query($conn, $sql);
    $clientes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $clientes[] = $row;
    }
    return $clientes;
}

// Función para eliminar un cliente
function eliminarCliente($conn, $id) {
    $sql = "DELETE FROM clientes WHERE idClientes = $id";
    if (mysqli_query($conn, $sql)) {
        $mostrarMensaje = false; // No mostrar el mensaje
        echo '<div id="mensajeEliminar" class="alert alert-success" role="alert">Cliente eliminado exitosamente.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error al eliminar el cliente.</div>';
    }
}

// Procesar la eliminación del cliente si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eliminar'])) {
    $cliente_id = $_POST['cliente_id'];
    eliminarCliente($conn, $cliente_id);
}

// Procesar el formulario de búsqueda si se envió
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['buscar'])) {
    $nombre = $_GET['nombre'];
    $correo_electronico = $_GET['correo_electronico'];
    $clientes = buscarClientes($conn, $nombre, $correo_electronico);
} else {
    $clientes = obtenerClientes($conn);
}

// Función para buscar clientes por nombre y/o correo electrónico
function buscarClientes($conn, $nombre, $correo_electronico) {
    $sql = "SELECT * FROM clientes WHERE nombre_cliente LIKE '%$nombre%' AND correo_electronico LIKE '%$correo_electronico%'";
    $result = mysqli_query($conn, $sql);
    $clientes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $clientes[] = $row;
    }
    return $clientes;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Clientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="mt-5">Registrar Cliente</h2>
        <form method="post">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nombre">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido_paterno">Apellido Paterno:</label>
                    <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido_materno">Apellido Materno:</label>
                    <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="correo_electronico">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico">
                </div>
                <div class="form-group col-md-4">
                    <label for="telefono">Teléfono:</label>
                    <input type="tel" class="form-control" id="telefono" name="telefono" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="domicilio">Domicilio:</label>
                    <input type="text" class="form-control" id="domicilio" name="domicilio" required>
                </div>
            </div>
            <button type="submit" class="btn btn-dark">Registrar Cliente</button>
        </form>

        <h2 class="mt-5">Buscar Clientes</h2>
        <form method="get">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="nombre_buscar">Nombre:</label>
                    <input type="text" class="form-control" id="nombre_buscar" name="nombre">
                </div>
                <div class="form-group col-md-4">
                    <label for="correo_buscar">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="correo_buscar" name="correo_electronico">
                </div>
                <div class="form-group col-md-4">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-dark" name="buscar">Buscar</button>
                </div>
            </div>
        </form>

        <h2 class="mt-5">Lista de Clientes</h2>
        <div class="table-responsive">
            <table id="tablaClientes" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Correo Electrónico</th>
                        <th>Teléfono</th>
                        <th>Domicilio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente) : ?>
                        <tr>
                            <td><?php echo $cliente['nombre_cliente']; ?></td>
                            <td><?php echo $cliente['apellido_paterno_cl']; ?></td>
                            <td><?php echo $cliente['apellido_materno_cl']; ?></td>
                            <td><?php echo $cliente['correo_electronico']; ?></td>
                            <td><?php echo $cliente['telefono']; ?></td>
                            <td><?php echo $cliente['domicilio']; ?></td>
                            <td>
                                <form method="post" style="display: inline;">
                                    <input type="hidden" name="cliente_id" value="<?php echo $cliente['idClientes']; ?>">
                                    <button type="submit" class="btn btn-danger" name="eliminar">Borrar</button>
                                </form>
                                <a href="modificarcliente.php?id=<?php echo $cliente['idClientes']; ?>" class="btn btn-primary">Modificar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Espera a que el DOM se cargue completamente
        document.addEventListener("DOMContentLoaded", function () {
            // Muestra el mensaje de registro
            var mensajeRegistro = document.getElementById('mensajeRegistro');
            if (mensajeRegistro) {
                mensajeRegistro.style.display = 'block'; // Muestra el mensaje
                setTimeout(function () {
                    mensajeRegistro.style.display = 'none'; // Oculta el mensaje después de 3 segundos
                }, 3000); // Cambia 3000 a la cantidad de milisegundos que desees mostrar el mensaje
            }

            // Muestra el mensaje de eliminación
            var mensajeEliminar = document.getElementById('mensajeEliminar');
            if (mensajeEliminar) {
                mensajeEliminar.style.display = 'block'; // Muestra el mensaje
                setTimeout(function () {
                    mensajeEliminar.style.display = 'none'; // Oculta el mensaje después de 3 segundos
                }, 3000); // Cambia 3000 a la cantidad de milisegundos que desees mostrar el mensaje
            }

            // Muestra el mensaje de campos incompletos
            var mensajeCampos = document.getElementById('mensajeCampos');
            if (mensajeCampos) {
                mensajeCampos.style.display = 'block'; // Muestra el mensaje
                setTimeout(function () {
                    mensajeCampos.style.display = 'none'; // Oculta el mensaje después de 3 segundos
                }, 3000); // Cambia 3000 a la cantidad de milisegundos que desees mostrar el mensaje
            }
        });
    </script>

</body>

</html>