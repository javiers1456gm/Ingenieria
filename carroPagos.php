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
        <form id="registroVentaForm" method="POST" action="registrar_venta.php">
            <div class="row">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "12345678";
                $dbname = "autoshop";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("La conexión ha fallado: " . $conn->connect_error);
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $autos_seleccionados = $_POST['autos_seleccionados'];
                    $ids_autos_str = implode(",", $autos_seleccionados);

                    $sql_autos = "SELECT idautos, marca, modelo, precio FROM autos WHERE idautos IN ($ids_autos_str)";
                    $result_autos = $conn->query($sql_autos);

                    if ($result_autos->num_rows > 0) {
                        while ($row_auto = $result_autos->fetch_assoc()) {
                            ?>
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $row_auto["marca"] . " " . $row_auto["modelo"]; ?></h5>
                                        <p class="card-text">Precio: $<?php echo $row_auto["precio"]; ?></p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="idautos[]" value="<?php echo $row_auto["idautos"]; ?>" id="auto_<?php echo $row_auto["idautos"]; ?>" checked>
                                            <label class="form-check-label" for="auto_<?php echo $row_auto["idautos"]; ?>">
                                                Seleccionar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
                ?>
            </div>
            <!-- Agrega un campo oculto para enviar los IDs de los autos seleccionados -->
            <?php foreach ($autos_seleccionados as $auto_id) { ?>
                <input type="hidden" name="autos_seleccionados[]" value="<?php echo $auto_id; ?>">
            <?php } ?>
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
                <button id="venta-autos" type="button" class="btn btn-dark">Registrar Venta</button>
            </div>
        </form>
    </div>

    <!-- Agregando Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Evento al hacer clic en "Venta De Auto(s)"
        document.getElementById('venta-autos').addEventListener('click', function(event) {
            event.preventDefault(); // Evita el comportamiento predeterminado del botón

            // Envía el formulario de registro de venta
            document.getElementById('registroVentaForm').submit();
        });
    </script>

</body>

</html>
