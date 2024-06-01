<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de citas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('fondo3.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .sidebar-icon {
            position: fixed;
            top: 7px;
            right: 25px;
            font-size: 2rem;
            cursor: pointer;
            color: #000;
        }

        .offcanvas {
            background-image: url('fondo3.jpg'); /* Cambia 'fondo-barra.jpg' por la ruta de tu imagen de fondo */
            background-size: cover;
            background-position: center;
            color: #fff; /* Texto blanco para mejor contraste */
        }
        .card {
            background-color: rgba(255, 255, 255, 0.8); /* Fondo blanco semitransparente para las tarjetas */
            color: #000; /* Texto negro */
            border: 4px;
        }
    </style>
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
                        <label for="cliente" style="color: white;">Cliente:</label>
                        <select class="form-control" id="cliente" name="cliente" required>
                            <option value="">Selecciona un cliente</option>
                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "autoshop";

                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("La conexión ha fallado: " . $conn->connect_error);
                            }

                            $sql = "SELECT idClientes, nombre_cliente, apellido_paterno_cl, apellido_materno_cl FROM Clientes";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["idClientes"] . "'>" . $row["nombre_cliente"] . " " . $row["apellido_paterno_cl"] . " " . $row["apellido_materno_cl"] . "</option>";
                                }
                            }

                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descripcion" style="color: white;">Descripcion:</label>
                        <input type="text" class="form-control" id="descripcion" name="descripcion" required placeholder="Ingrese una descripcion">
                    </div>
                    <div class="form-group">
                        <label for="time" style="color: white;">Hora:</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                    </div>
                    <div class="form-group">
                        <label for="vendedor" style="color: white;">Vendedor Disponible:</label>
                        <select class="form-control" id="vendedor" name="vendedor" required>
                            <option value="">Selecciona un vendedor</option>
                            <?php
                            $servername = "localhost";
                            $username = "root";
                            $password = "";
                            $dbname = "autoshop";

                            $conn = new mysqli($servername, $username, $password, $dbname);

                            if ($conn->connect_error) {
                                die("La conexión ha fallado: " . $conn->connect_error);
                            }

                            $sql = "SELECT idUsuarios, nombre_vendedor FROM Usuarios";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row["idUsuarios"] . "'>" . $row["nombre_vendedor"] . "</option>";
                                }
                            }

                            $conn->close();
                            ?>
                        </select>
                    </div>
                    <input type="hidden" id="fecha" name="fecha" value="">
                    <div class="form-group">
                        <button type="submit" class="btn btn-dark">Guardar</button>
                        <button type="button" class="btn btn-dark" onclick="actualizarCita();">Actualizar</button>
                        <button type="button" class="btn btn-dark" onclick="eliminarCita();">Borrar</button>
                        <input type="hidden" name="delete">
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <br><br>
                <div class="container right">
                    <?php include 'calendario.php'; ?>
                </div>
            </div>
        </div>
    </div>
    <i class="bi bi-list sidebar-icon" data-bs-toggle="offcanvas" data-bs-target="#citasOffcanvas" aria-controls="citasOffcanvas"></i>

<div class="offcanvas offcanvas-end" tabindex="-1" id="citasOffcanvas" aria-labelledby="citasOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="citasOffcanvasLabel">Citas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <input type="text" id="filtroCitas" class="form-control mb-3" placeholder="Buscar por cliente o vendedor...">
        <div id="listaCitas">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "autoshop";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("La conexión ha fallado: " . $conn->connect_error);
            }

            $sql = "SELECT c.idCitas, c.descripcion, c.hora, cl.nombre_cliente, cl.apellido_paterno_cl, cl.apellido_materno_cl, u.nombre_vendedor
                    FROM Citas c
                    JOIN Clientes cl ON c.idClientes = cl.idClientes
                    JOIN Usuarios u ON c.idUsuarios = u.idUsuarios
                    ORDER BY c.hora";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='card mb-3 cita-item'>";
                    echo "<div class='card-body'>";
                    echo "<h5 class='card-title'>" . $row["nombre_cliente"] . " " . $row["apellido_paterno_cl"] . " " . $row["apellido_materno_cl"] . "</h5>";
                    echo "<p class='card-text'>" . $row["descripcion"] . "</p>";
                    echo "<p class='card-text'><small class='text-muted'> " . $row["hora"] . "</small></p>";
                    echo "<p class='card-text'><small class='text-muted'>Vendedor: " . $row["nombre_vendedor"] . "</small></p>";
                    echo "<button class='btn btn-primary btn-atender' onclick='atenderCita(" . $row["idCitas"] . ")'>Atender</button>";
                    echo "</div>";
                    echo "</div>";
                }

            } else {
                echo "<p>No hay citas programadas.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>


    <script>
         function atenderCita(idCita) {
        // Redirigir al usuario a buscarAuto.php con el ID de la cita como parámetro
        window.location.href = "buscarAuto.php?idCita=" + idCita;
    }
       
       function selectDay(dayElement) {
            var allDays = document.querySelectorAll('.calendar-day');
            allDays.forEach(function(day) {
                day.classList.remove('selected');
            });

            dayElement.parentNode.classList.add('selected');

            var today = new Date();
            var year = today.getFullYear();
            var month = today.getMonth() + 1;
            var day = parseInt(dayElement.textContent.trim());

            var selectedDate = year + '-' + (month < 10 ? '0' + month : month) + '-' + (day < 10 ? '0' + day : day);

            document.getElementById('fecha').value = selectedDate;

            console.log("Fecha seleccionada:", selectedDate);
        }

        function eliminarCita() {
            var cliente = document.getElementById("cliente").value;
            document.querySelector("input[name='delete']").value = "true";
            var deleteValue = document.querySelector("input[name='delete']").value;

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
                }
            };
            xhr.send("cliente=" + cliente + "&delete=" + deleteValue);
            limpiarCampos();
        }

        function limpiarCampos() {
            document.getElementById("cliente").value = "";
            document.getElementById("descripcion").value = "";
            document.getElementById("time").value = "";
            document.getElementById("vendedor").value = "";
            document.getElementById("fecha").value = "";
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("citaForm").addEventListener("submit", function(event) {
                event.preventDefault();

                var formData = new FormData(this);

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

            document.getElementById('filtroCitas').addEventListener('input', function() {
                var filterValue = this.value.toLowerCase();
                var citas = document.querySelectorAll('.cita-item');

                citas.forEach(function(cita) {
                    var textoCita = cita.textContent.toLowerCase();
                    if (textoCita.includes(filterValue)) {
                        cita.style.display = '';
                    } else {
                        cita.style.display = 'none';
                    }
                });
            });
        });

        function actualizarCita() {
            var cliente = document.getElementById("cliente").value;
            var formData = new FormData(document.getElementById("citaForm"));
            formData.append("cliente", cliente);

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>