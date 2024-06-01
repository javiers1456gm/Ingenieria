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
            echo '<div class="alert alert-success" role="alert">Cliente registrado exitosamente.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error al registrar el cliente.</div>';
        }

        // Cerrar la declaración preparada
        $stmt->close();
    } else {
        echo '<div class="alert alert-danger" role="alert">Por favor complete todos los campos del formulario.</div>';
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
        echo '<div class="alert alert-success" role="alert">Cliente eliminado exitosamente.</div>';
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

// Función para actualizar un cliente
function actualizarCliente($conn, $id, $nombre, $apellido_paterno, $apellido_materno, $correo_electronico, $telefono, $domicilio) {
    $sql = "UPDATE clientes SET nombre_cliente=?, apellido_paterno_cl=?, apellido_materno_cl=?, correo_electronico=?, telefono=?, domicilio=? WHERE idClientes=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $nombre, $apellido_paterno, $apellido_materno, $correo_electronico, $telefono, $domicilio, $id);
    if ($stmt->execute()) {
        $mostrarMensaje = false; // No mostrar el mensaje
        echo '<div class="alert alert-success" role="alert">Cliente actualizado exitosamente.</div>';
    } else {
        echo '<div class="alert alert-danger" role="alert">Error al actualizar el cliente.</div>';
    }
    $stmt->close();
}

// Procesar el formulario de actualización si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $cliente_id = $_POST['cliente_id_actualizar'];
    $nombre = $_POST['nombre_actualizar'];
    $apellido_paterno = $_POST['apellido_paterno_actualizar'];
    $apellido_materno = $_POST['apellido_materno_actualizar'];
    $correo_electronico = $_POST['correo_electronico_actualizar'];
    $telefono = $_POST['telefono_actualizar'];
    $domicilio = $_POST['domicilio_actualizar'];
    actualizarCliente($conn, $cliente_id, $nombre, $apellido_paterno, $apellido_materno, $correo_electronico, $telefono, $domicilio);
}
?>
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Clientes</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<style>
     .table-bordered tbody tr:hover {  
            cursor: pointer;
            background-color: #f5f5f5;
            border: gray;
            border: radius 1px;
        }

        .tablaClientes {
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

        <h2 class="mt-5">Actualizar Cliente</h2>
        <form method="post" id="formActualizarCliente" style="display:none;">
            <div class="form-row">
                <div class="form-group col-md-4">
                <input type="hidden" name="cliente_id_actualizar" id="cliente_id_actualizar">
                    <label for="nombre_actualizar">Nombre:</label>
                    <input type="text" class="form-control" id="nombre_actualizar" name="nombre_actualizar" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido_paterno_actualizar">Apellido Paterno:</label>
                    <input type="text" class="form-control" id="apellido_paterno_actualizar" name="apellido_paterno_actualizar" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="apellido_materno_actualizar">Apellido Materno:</label>
                    <input type="text" class="form-control" id="apellido_materno_actualizar" name="apellido_materno_actualizar" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="correo_electronico_actualizar">Correo Electrónico:</label>
                    <input type="email" class="form-control" id="correo_electronico_actualizar" name="correo_electronico_actualizar">
                </div>
                <div class="form-group col-md-4">
                    <label for="telefono_actualizar">Teléfono:</label>
                    <input type="tel" class="form-control" id="telefono_actualizar" name="telefono_actualizar" required>
                </div>
                <div class="form-group col-md-4">
                    <label for="domicilio_actualizar">Domicilio:</label>
                    <input type="text" class="form-control" id="domicilio_actualizar" name="domicilio_actualizar" required>
                </div>
            </div>
            <button type="submit" class="btn btn-dark">Actualizar Cliente</button>
            <button type="button" class="btn btn-secondary" id="cancelarActualizacion">Cancelar</button>
        </form>

        <h2 class="mt-5">Lista de Clientes</h2>
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
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tablaClientes = document.getElementById("tablaClientes");
            var formularioCliente = document.querySelector("form[method='post']");
            var formActualizarCliente = document.getElementById("formActualizarCliente");

            tablaClientes.addEventListener("click", function (event) {
                var fila = event.target.closest("tr");
                if (!fila) return;

                var idCliente = fila.getAttribute("data-id");
                var datosCliente = {
                    nombre: fila.cells[0].textContent,
                    apellido_paterno: fila.cells[1].textContent,
                    apellido_materno: fila.cells[2].textContent,
                    correo_electronico: fila.cells[3].textContent,
                    telefono: fila.cells[4].textContent,
                    domicilio: fila.cells[5].textContent,
                };

                for (var campo in datosCliente) {
                    var input = formActualizarCliente.querySelector("[name='" + campo + "_actualizar']");
                    if (input) {
                        input.value = datosCliente[campo];
                    }
                }

                // Mostrar el formulario de actualización y ocultar el de registro
                formActualizarCliente.style.display = 'block';
                formularioCliente.style.display = 'none';
            });

            // Botón para cancelar la actualización
            document.getElementById("cancelarActualizacion").addEventListener("click", function () {
                // Ocultar el formulario de actualización y mostrar el de registro
                formActualizarCliente.style.display = 'none';
                formularioCliente.style.display = 'block';
            });

            // Botones de actualizar
            var btnsActualizar = document.querySelectorAll(".btn-actualizar");
            btnsActualizar.forEach(function (btn) {
                btn.addEventListener("click", function (event) {
                    var idCliente = this.getAttribute("data-id");

                    // Colocar el ID del cliente en un campo oculto del formulario
                    var inputClienteId = document.createElement("input");
                    inputClienteId.type = "hidden";
                    inputClienteId.name = "cliente_id_actualizar";
                    inputClienteId.value = idCliente;
                    formActualizarCliente.appendChild(inputClienteId);

                    // Enviar el formulario de actualización
                    formActualizarCliente.submit();
                });
            });
        });
    </script>
</body>

</html>
