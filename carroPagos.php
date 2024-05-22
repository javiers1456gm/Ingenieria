<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Venta</title>
    <!-- Agregando Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php require 'navBar.php'; ?>
</head>

<body>

    <div class="container mt-5">
        <h2>Pago De Autos</h2>
        <!-- Formulario de registro de venta -->
        <form method="POST" action="registrar_venta.php">
            <div class="form-group">
                <label for="idautos">Autos:</label>
                <select class="form-control" id="idautos" name="idautos[]" multiple required onchange="calcularTotal()">
                    <?php
                    // Conectar a la base de datos y obtener los autos disponibles
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "autoshop";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("La conexión ha fallado: " . $conn->connect_error);
                    }

                    $sql_autos = "SELECT idautos, marca, modelo, precio FROM autos";
                    $result_autos = $conn->query($sql_autos);

                    if ($result_autos->num_rows > 0) {
                        while ($row_auto = $result_autos->fetch_assoc()) {
                            echo "<option value='" . $row_auto["idautos"] . "' data-precio='" . $row_auto["precio"] . "'>" . $row_auto["marca"] . " " . $row_auto["modelo"] . " - $" . $row_auto["precio"] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="cantidad_pagos">Cantidad de Pagos:</label>
                <input type="number" class="form-control" id="cantidad_pagos" name="cantidad_pagos" required placeholder="Ingrese la cantidad de pagos">
            </div>
            <div class="form-group">
                <label for="tipo_pago">Tipo de Pago:</label>
                <select class="form-control" id="tipo_pago" name="tipo_pago" required>
                    <option value="">Seleccionar Tipo de Pago</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta">Tarjeta</option>
                    <!-- Agrega más opciones si es necesario -->
                </select>
            </div>
            <div class="form-group">
                <label for="total_pagar">Total a Pagar:</label>
                <input type="text" class="form-control" id="total_pagar" name="total_pagar" readonly>
            </div>
            <div class="form-group">
                <label for="monto_pago">Monto de Pago Inicial:</label>
                <input type="number" step="0.01" class="form-control" id="monto_pago" name="monto_pago" required placeholder="Ingrese el monto de pago inicial">
            </div>
            <div class="form-group">
                <label for="fecha_pago">Fecha de Pago:</label>
                <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" required>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-dark">Registrar Venta</button>
            </div>
        </form>
    </div>

    <!-- Agregando Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Función para calcular el total a pagar en tiempo real
        function calcularTotal() {
            var autosSeleccionados = document.getElementById("idautos").selectedOptions;
            var total = 0;

            for (var i = 0; i < autosSeleccionados.length; i++) {
                total += parseFloat(autosSeleccionados[i].getAttribute("data-precio"));
            }

            document.getElementById("total_pagar").value = total.toFixed(2);
        }
    </script>

</body>

</html>

<?php
// Verificar si se recibieron datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conectar a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "12345678";
    $dbname = "autoshop";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("La conexión ha fallado: " . $conn->connect_error);
    }

    // Obtener los datos del formulario
    $idautos = $_POST['idautos'];
    $fecha_pago = $_POST['fecha_pago'];
    $monto_pago = $_POST['monto_pago'];
    $tipo_pago = $_POST['tipo_pago'];
    $cantidad_pagos = $_POST['cantidad_pagos'];

    // Preparar y ejecutar la consulta SQL para insertar la venta en la tabla pagos
    $sql = "INSERT INTO pagos (idautos, fecha_pago, monto_pago, tipo_pago, cantidad_pagos) VALUES ('$idautos', '$fecha_pago', '$monto_pago', '$tipo_pago', '$cantidad_pagos')";

    if ($conn->query($sql) === TRUE) {
        echo "La venta se registró correctamente.";
    } else {
        echo "Error al registrar la venta: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
}
?>
