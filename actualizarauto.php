<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Autos</title>
    <!-- Agregando Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h2>Registro de Autos</h2>
        <!-- Formulario de registro de autos -->
        <form method="POST" action="registrar_auto.php">
            <div id="formularioautos" class="form-group">
                <label for="tipo_concesion">Tipo de Concesión:</label>
                <select class="form-control" id="tipo_concesion" name="tipo_concesion" required>
                    <option value="">Seleccionar Tipo de Concesión</option>
                    <?php
                    // Aquí se realiza la conexión a la base de datos y se obtienen los tipos de concesión
                    // Conexión a la base de datos
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "autoshop";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("La conexión ha fallado: " . $conn->connect_error);
                    }

                    // Consulta SQL para obtener los tipos de concesión
                    $sql = "SELECT idtipoconcesion, tipo FROM tipo_concesion";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["idtipoconcesion"] . "'>" . $row["tipo"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" class="form-control" id="marca" name="marca" required
                    placeholder="Ingrese la marca del auto">
            </div>
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" required
                    placeholder="Ingrese el modelo del auto">
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" required
                    placeholder="Ingrese el precio del auto">
            </div>
            <div class="form-group">
                <label for="anio">Año:</label>
                <input type="number" class="form-control" id="anio" name="anio" required
                    placeholder="Ingrese el año del auto">
            </div>


            <div class="text-center mt-3">
                <button type="submit" class="btn btn-dark">Registrar</button>

            </div>

        </form>

    </div>

    <!-- Lista de autos existentes -->
    <div class="mt-5">
        <h3>Autos Existentes</h3>
        <ul class="list-group" id="autosList">
            <!-- Aquí se generan las filas de la lista desde la base de datos -->
            <?php
            // Consulta SQL para obtener los autos existentes
            $sql_autos = "SELECT idautos, marca, modelo, precio, anio FROM autos";
            $result_autos = $conn->query($sql_autos);

            if ($result_autos->num_rows > 0) {
                while ($row_auto = $result_autos->fetch_assoc()) {
                    echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                    echo $row_auto["idautos"]." ".$row_auto["marca"] . " " . $row_auto["modelo"] . " (" . $row_auto["anio"] . ") - $" . $row_auto["precio"];
                    echo "<div>";
                    // Agregamos los botones para eliminar y actualizar, pasando el ID del auto como parámetro
                    echo "<form class='d-inline' method='post' action='eliminarauto.php'>";
                    echo "<button class='btn btn-danger btn-sm mr-2' onclick='eliminarAuto(" . $row_auto["precio"] . ")'>Eliminar</button>";
                    echo "<input type='hidden' name='delete' value='true'>"; // Agregado para indicar que es una solicitud de eliminación
                    echo "<button class='btn btn-primary btn-sm' onclick='actualizarAuto(" . $row_auto["idautos"] . ")'>Actualizar</button>";
                    echo "</div>";
                    echo "</li>";
                }
            } else {
                echo "<li class='list-group-item'>No se encontraron autos.</li>";
            }
            ?>
        </ul>
    </div>

    <!-- Agregando Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Función para eliminar un auto
        function eliminarAuto(idautos) {
            // Redireccionar a eliminarautos.php con el ID del auto como parámetro
            window.location.href = 'eliminarautos.php?id_auto=' + idautos;
        }

        // Función para actualizar un auto
        function actualizarAuto(idautos) {
            // Obtener los datos del formulario incluyendo la imagen
            var formulario = document.getElementById("formularioautos");
            var formData = new FormData(formulario);

            // Agregar el id del usuario a actualizar
            formData.append('idautos', idautos);

            // Enviar los datos mediante AJAX a ActualizarUsuarios.php
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Manejar la respuesta del servidor si es necesario
                        console.log(xhr.responseText);
                        // Recargar la página o realizar otra acción si es necesario
                        location.reload();
                    } else {
                        // Manejar errores si es necesario
                        console.error('Hubo un error al actualizar el auto.');
                    }
                }
            };
            xhr.open('POST', 'actualizarauto.php');
            xhr.send(formData);
        }
        
    </script>


</body>

</html>