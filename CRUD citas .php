<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de citas</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<?php include 'navBar.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2>Cita</h2>
                <form id="citaForm" action="insertarCitas.php" method="POST" enctype="multipart/form-data">
                    <div id="citaIdDiv" style="display: none;">
                        <div class="form-group">
                            <label for="citaId">ID de la Cita:</label>
                            <input type="text" class="form-control" id="citaId" name="citaId" placeholder="Ingrese el ID de la cita">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="cliente">Cliente:</label>
                        <select class="form-control" id="cliente" name="cliente" required>
                            <option value="">Selecciona un cliente</option>
                            <?php
                            // Conexión a la base de datos (debes modificar estos valores según tu configuración)
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "autoshop";

                            // Crear conexión
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            // Verificar conexión
                            if ($conn->connect_error) {
                                die("La conexión ha fallado: " . $conn->connect_error);
                            }

                            // Consulta SQL para obtener los clientes de la base de datos
                            $sql = "SELECT idClientes, nombre_cliente,apellido_paterno_cl,apellido_materno_cl FROM Clientes";
                            $result = $conn->query($sql);

                            // Generar opciones para el combobox
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["idClientes"] . "'>" . $row["nombre_cliente"] . " " . $row["apellido_paterno_cl"] . " " . $row["apellido_materno_cl"] . "</option>";
                                }
                            }


                            // Cerrar conexión
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="email">Descripcion:</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required placeholder="Ingrese una descripcion">
                    </div>
                    <div class="form-group">
                        <label for="time">Hora:</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>
                    <div class="form-group">
                        <label for="vendedor">Vendedor Disponible:</label>
                        <select class="form-control" id="vendedor" name="vendedor" required>
                            <option value="">Selecciona un vendedor</option>
                            <?php
                            // Conexión a la base de datos (debes modificar estos valores según tu configuración)
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "autoshop";

                            // Crear conexión
                            $conn = new mysqli($servername, $username, $password, $dbname);

                            // Verificar conexión
                            if ($conn->connect_error) {
                                die("La conexión ha fallado: " . $conn->connect_error);
                            }

                            // Consulta SQL para obtener los vendedores de la base de datos
                            $sql = "SELECT idUsuarios, nombre_vendedor FROM Usuarios";
                            $result = $conn->query($sql);

                            // Generar opciones para el combobox
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["idUsuarios"] . "'>" . $row["nombre_vendedor"] . "</option>";
                                }
                            }

                            // Cerrar conexión
                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <!-- Campo oculto para almacenar la fecha seleccionada -->
                    <input type="hidden" id="fecha" name="fecha" value="">
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark">Guardar</button>
                        <button type="button" class="btn btn-dark" onclick="actualizarCita();">Actualizar</button>
                        <button type="button" class="btn btn-dark" onclick="eliminarCita();">Borrar</button>
                        <!-- Campo oculto para indicar la eliminación -->
                        <input type="hidden" name="delete">
                        
                    </div>
                    
                </form>
            </div>
            <div class="col-md-6">
                <br>
                <br>    
                <div class="container right">
                    <div class="row">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <!-- Contenedor en la parte derecha -->
                            <div class="sidebar">
                                <!-- Aquí incluimos el calendario -->
                                <?php include 'calendario.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para seleccionar la fecha y mostrar/ocultar el campo de ID de cita -->
    <script>
        function selectDay(dayElement) {
            // Elimina la clase 'selected' de todos los días
            var allDays = document.querySelectorAll('.calendar-day');
            allDays.forEach(function(day) {
                day.classList.remove('selected');
            });

            // Agrega la clase 'selected' al día clickeado
            dayElement.parentNode.classList.add('selected');

            // Obtener la fecha actual
            var today = new Date();
            var year = today.getFullYear();
            var month = today.getMonth() + 1; // El mes se indexa desde 0, entonces sumamos 1
            var day = parseInt(dayElement.textContent.trim()); // Obtener el día del calendario

            // Formatear la fecha en el formato YYYY-MM-DD
            var selectedDate = year + '-' + (month < 10 ? '0' + month : month) + '-' + (day < 10 ? '0' + day : day);

            // Actualiza el valor del campo oculto 'fecha'
            document.getElementById('fecha').value = selectedDate;

            // Imprime la fecha seleccionada en la consola para verificar
            console.log("Fecha seleccionada:", selectedDate);
        }

        function eliminarCita() {
            // Obtener el nombre del cliente
            var cliente = document.getElementById("cliente").value;

            // Establecer el valor del campo oculto "delete"
            document.querySelector("input[name='delete']").value = "true";

            // Obtener el valor del campo "delete"
            var deleteValue = document.querySelector("input[name='delete']").value;

            // Enviar los datos al script PHP para eliminar la cita de la base de datos
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "EliminarCitas.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText.trim() === "Registro eliminado correctamente") {
                        alert("Registro eliminado correctamente");
                    } else {
                        alert("Error al eliminar el registro:\n" + xhr.responseText);
                    }
                    // Aquí puedes agregar cualquier lógica adicional después de eliminar la cita
                }
            };
            xhr.send("cliente=" + cliente + "&delete=" + deleteValue); // Agregar el parámetro delete
            limpiarCampos();
        }
        function limpiarCampos() {
            document.getElementById("cliente").value = "";
            document.getElementById("descripcion").value = "";
            document.getElementById("time").value = "";
            document.getElementById("vendedor").value = "";
            document.getElementById("fecha").value = "";
        }
    </script>
    <!-- Script para enviar los datos del formulario mediante AJAX -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("citaForm").addEventListener("submit", function(event) {
                event.preventDefault(); // Evita que el formulario se envíe de forma convencional

                // Obtener los datos del formulario
                var formData = new FormData(this);

                // Enviar los datos del formulario mediante AJAX
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "insertarCitas.php", true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        if (xhr.responseText.trim() === "success") {
                            alert("Nuevo registro insertado correctamente");
                            limpiarCampos();
                        } else {
                            alert("Error al insertar datos:\n" + xhr.responseText);
                        }
                    }
                };
                xhr.send(formData);
            });
        });
    </script>
    <!-- Script para enviar la actualización de la cita mediante AJAX -->
    <script>
        function actualizarCita() {
            // Obtener el valor del cliente
            var cliente = document.getElementById("cliente").value;

            // Obtener los datos del formulario
            var formData = new FormData(document.getElementById("citaForm"));

            // Enviar el valor del cliente junto con los demás datos del formulario mediante AJAX
            formData.append("cliente", cliente);

            // Enviar los datos del formulario mediante AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "ActualizarCitas.php", true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    if (xhr.responseText.trim() === "success") {
                        alert("Registro actualizado correctamente");
                    } else {
                        alert("Error al actualizar datos:\n" + xhr.responseText);
                    }
                }
            };
            xhr.send(formData);
            limpiarCampos();
        }
    </script>
</body>
</html>
