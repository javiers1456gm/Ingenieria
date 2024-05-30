<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Autos</title>
    <!-- Agregando Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
     body {
    background-image: url('fondo3.jpg'); /* Cambia 'ruta/a/tu/imagen.jpg' por la ruta de tu imagen */
    background-size: cover; /* Asegura que la imagen cubra todo el fondo */
    background-position: center; /* Centra la imagen */
    background-repeat: no-repeat; /* Evita que la imagen se repita */
    background-attachment: fixed; /* Hace que la imagen de fondo se mantenga fija al hacer scroll */
}
</style>

<body>
<?php include 'navBar.php'; ?>
    <div class="container mt-5">
        <h2>Registro de Autos</h2>
        <!-- Formulario de registro de autos -->
        <form method="POST" action="registrar_auto.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="tipo_concesion">Tipo de Concesión:</label>
                <select class="form-control" id="tipo_concesion" name="tipo_concesion" required onchange="mostrarCamposAdicionales(this)">
                    <option value="">Seleccionar Tipo de Concesión</option>
                    <!-- Código PHP para cargar los tipos de concesión -->
                    <?php
                    // Realiza la conexión a la base de datos
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
            <!-- Campos adicionales que se mostrarán según el tipo de concesión -->
            <div class="form-group" id="campo_dueño" style="display: none;">
                <label for="dueño">Dueño:</label>
                <select class="form-control" id="dueño" name="dueño">
                    <!-- Código PHP para cargar los dueños dinámicamente -->
                    <?php
                    // Consulta SQL para obtener los tipos de concesión
                    $sql = "SELECT iddueno, nombre_dueno,apellido_paterno,apellido_materno FROM dueno";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["iddueno"] . "'>" . $row["nombre_dueno"] . " ".$row["apellido_paterno"]." ".$row["apellido_materno"]. "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group" id="matricula" style="display: none;">
                <label for="matricula">Número de Serie:</label>
                <input type="text" class="form-control" id="matricula" name="matricula" placeholder="Ingrese el número de serie del auto">
            </div>
            <!-- Campos comunes para todos los autos -->
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" class="form-control" id="marca" name="marca" required placeholder="Ingrese la marca del auto">
            </div>
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" required placeholder="Ingrese el modelo del auto">
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" required placeholder="Ingrese el precio del auto">
            </div>
            <div class="form-group">
                <label for="anio">Año:</label>
                <input type="number" class="form-control" id="anio" name="anio" required placeholder="Ingrese el año del auto">
            </div>

            <div class="form-group">
    <div class="custom-file">
        <input type="file" class="custom-file-input" id="archivo" name="archivos[]" multiple>
        <label class="custom-file-label" for="archivo">Seleccionar archivos</label>
    </div>
</div>



            <div class="text-center mt-3">
                <button type="submit" class="btn btn-dark">Registrar</button>
            </div>

        </form>
    </div>

    <!-- Lista de autos existentes -->
    <div class="mt-5 text-center">
        <h3>Autos Existentes</h3>
        <ul class="list-group mx-auto" style="width: fit-content;">
        <?php
        // Consulta SQL para obtener los autos existentes junto con sus fotos
        $sql_autos = "SELECT 
            autos.idautos,
            autos.idtipoconcesion,
            autos.iddueno,
            autos.matricula,
            autos.marca,
            autos.modelo,
            autos.precio,
            autos.anio,
            fotos_auto.foto AS foto
        FROM 
            autos
        LEFT JOIN 
            fotos_auto ON autos.idautos = fotos_auto.idautos
        GROUP BY 
            autos.idautos";
        $result_autos = $conn->query($sql_autos);

        if ($result_autos && $result_autos->num_rows > 0) {
            while ($row_auto = $result_autos->fetch_assoc()) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                // Mostrar imagen del auto utilizando la ruta de la imagen almacenada en la base de datos
                if (!empty($row_auto['foto'])) {
                    echo "<img src='" . $row_auto['foto'] . "' alt='Imagen del auto' style='max-width: 100px;'>";
                } else {
                    echo "<img src='default.jpg' alt='Imagen del auto' style='max-width: 100px;'>"; // Ruta de una imagen por defecto si no hay foto
                }

                echo $row_auto["idautos"] . " " . $row_auto["marca"] . " " . $row_auto["modelo"] . " (" . $row_auto["anio"] . ") - $" . $row_auto["precio"];
                echo "<div>";
                // Agregamos los botones para eliminar y actualizar, pasando el ID del auto como parámetro
                echo "<form class='d-inline' method='post' action='eliminarauto.php'>";
                echo "<button type='submit' class='btn btn-danger btn-sm mr-2' onclick='return confirm(\"¿Estás seguro de que quieres eliminar este auto?\")'>Eliminar</button>";
                echo "<input type='hidden' name='idautos' value='" . $row_auto["idautos"] . "'>";
                echo "<input type='hidden' name='delete' value='true'>"; // Agregado para indicar que es una solicitud de eliminación
                echo "</form>";
                // Formulario para actualizar el auto
                echo "<form class='d-inline' onsubmit='event.preventDefault(); actualizarAuto(" . $row_auto["idautos"] . ");'>";
                echo "<button type='submit' class='btn btn-primary btn-sm'>Actualizar</button>";
                echo "</form>";
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function mostrarCamposAdicionales(select) {
            var selectedOption = select.options[select.selectedIndex];
            var campoDueño = document.getElementById("campo_dueño");
            var campoNumeroSerie = document.getElementById("matricula");

            // Verificar si el tipo de concesión seleccionado es igual a 1
            if (selectedOption.value === "1") {
                campoDueño.style.display = "block";
                campoNumeroSerie.style.display = "block";
            } else {
                campoDueño.style.display = "none";
                campoNumeroSerie.style.display = "none";
            }
        }

        function actualizarAuto(idAuto) {
    // Obtener los datos del formulario
    var formulario = document.querySelector('form[action="registrar_auto.php"]');
    var formData = new FormData(formulario);

    // Agregar el ID del auto al FormData
    formData.append('idautos', idAuto);

    // Enviar los datos mediante AJAX a actualizar_auto.php
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
