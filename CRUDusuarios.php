<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuarios</title>
  <!-- Agregando Bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navBar.php'; ?>
<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "autoshop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

// Consulta SQL para obtener los roles
$sql = "SELECT idroles, nombre_rol FROM roles";
$result = $conn->query($sql);
?>

<div class="container mt-5">
  <div class="row">
    <div class="col-md-6">
      <h2>Usuario</h2>
      <form id="formulario-usuario" method="POST" enctype="multipart/form-data" action="InsertarUsuarios.php">
        <div class="form-group">
          <label for="rol">Nombre de Rol:</label>
          <select class="form-control" id="rol" name="rol" required>
            <option value="">Seleccionar Rol</option>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["idroles"] . "'>" . $row["nombre_rol"] . "</option>";
                }
            }
            ?>
          </select>
        </div>
        <div class="form-group">
          <label for="nombre">Nombre:</label>
          <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Ingrese un nombre">
        </div>
        <div class="form-group">
          <label for="apellido_paterno">Apellido paterno:</label>
          <input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" required placeholder="Ingrese un apellido">
        </div>
        <div class="form-group">
          <label for="apellido_materno">Apellido materno:</label>
          <input type="text" class="form-control" id="apellido_materno" name="apellido_materno" required placeholder="Ingrese un apellido">
        </div>
        <div class="form-group">
          <label for="horario_entrada">Horario de entrada:</label>
          <input type="time" class="form-control" id="horario_entrada" name="horario_entrada" required placeholder="Ingrese una hora de entrada">
        </div>
        <div class="form-group">
          <label for="horario_salida">Horario de salida:</label>
          <input type="time" class="form-control" id="horario_salida" name="horario_salida" required placeholder="Ingrese una hora de salida">
        </div>
        <div class="form-group">
          <label for="fecha_ingreso">Fecha de ingreso:</label>
          <input type="date" class="form-control" id="fecha_ingreso" name="fecha_ingreso" required placeholder="Ingrese una fecha">
        </div>
        <div class="form-group">
          <label for="estatus_Usuario">Estatus:</label>
          <input type="text" class="form-control" id="estatus_usuario" name="estatus_usuario" required placeholder="Ingrese el estatus del usuario">
        </div>
        <div class="form-group">
          <label for="contrasena">Contraseña:</label>
          <input type="password" class="form-control" id="contrasena" name="contrasena" required placeholder="Ingrese una contraseña">
        </div>
        <div class="form-group">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="archivo" name="archivo">
            <label class="custom-file-label" for="archivo">Seleccionar archivo</label>
          </div>
        </div>
        <button type="submit" class="btn btn-dark">Guardar</button>
      </form>
    </div>
    <!-- Usuarios Existentes -->
    <div class="col-md-6">
      <h2>Usuarios Existentes</h2>
      <div class="list-group">
        <?php
        // Consulta SQL para obtener los usuarios existentes
        // Consulta SQL para obtener los usuarios existentes
$sql_usuarios = "SELECT idUsuarios, CONCAT(nombre_vendedor , ' ', apellido_paterno, ' ', apellido_materno) AS nombre_completo, roles.nombre_rol, foto FROM usuarios JOIN roles ON usuarios.idroles = roles.idroles";

$result_usuarios = $conn->query($sql_usuarios);

if ($result_usuarios->num_rows > 0) {
    while($row_usuario = $result_usuarios->fetch_assoc()) {
        echo "<div class='list-group-item'>";
        echo "<h5 class='mb-1'>" . $row_usuario["nombre_completo"] . "</h5>";
        echo "<p class='mb-1'>Rol: " . $row_usuario["nombre_rol"] . "</p>";
        echo "<img src='" . $row_usuario["foto"] . "' class='rounded-circle' alt='Foto de perfil' width='50'>";
        echo "<form class='d-inline' method='post' action='EliminarUsuarios.php'>";
        echo "<input type='hidden' name='idUsuario' value='" . $row_usuario["idUsuarios"] . "'>";
        echo "<input type='hidden' name='delete' value='true'>"; // Agregado para indicar que es una solicitud de eliminación
        echo "<button type='submit' class='btn btn-danger btn-sm mr-2'>Eliminar</button>";
        echo "</form>";
        echo "<button class='btn btn-primary btn-sm' onclick='actualizarUsuario(" . $row_usuario["idUsuarios"] . ")'>Actualizar</button>";
        echo "</div>";
    }
} else {
    echo "<p>No se encontraron usuarios.</p>";
}

        ?>
      </div>
    </div>
  </div>
</div>

<!-- Agregando Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
function actualizarUsuario(idUsuario) {
  // Obtener los datos del formulario incluyendo la imagen
  var formulario = document.getElementById("formulario-usuario");
  var formData = new FormData(formulario);

  // Agregar el id del usuario a actualizar
  formData.append('idUsuario', idUsuario);

  // Enviar los datos mediante AJAX a ActualizarUsuarios.php
  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // Manejar la respuesta del servidor si es necesario
        console.log(xhr.responseText);
        // Recargar la página o realizar otra acción si es necesario
        location.reload();
      } else {
        // Manejar errores si es necesario
        console.error('Hubo un error al actualizar el usuario.');
      }
    }
  };
  xhr.open('POST', 'ActualizarUsuarios.php');
  xhr.send(formData);
}
</script>
</body>
</html>

<?php
// Cerrar la conexión a la base de datos
$conn->close();
?>