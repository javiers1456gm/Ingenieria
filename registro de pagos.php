<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reporte de Pagos</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />

    <style>
        .table-hover tbody tr:hover {
            cursor: pointer;
            background-color: #f5f5f5;
        }

        .table-selected {
            background-color: #e9ecef !important;
        }
    </style>
</head>

<body>
<?php
require 'navBar.php';
?>

<div class="container" style="margin-top: 70px;">
    <div class="row">
        <!-- Contenedor izquierdo con los inputs -->
        <div class="col-md-6">
            <h2 class="text-center">Pago auto</h2>
            <form action="registro de pagos.php" method="post" enctype="multipart/form-data">
                <br>
                <div class="form-group">
                    <label for="dueno">Nombre del dueño:</label>
                    <!-- Campo de texto del nombre del dueño con evento input -->
                    <input type="text" class="form-control" id="dueno" name="dueno" placeholder="Ingrese el nombre del dueño" oninput="buscarAutos()">
                </div>
                <br>
                <div class="form-group">
                    <label for="auto">Auto a pagar del dueño:</label>
                    <select class="form-select" id="auto" name="auto">
                        <!-- Las opciones se llenarán dinámicamente mediante JavaScript -->
                    </select>
                </div>

                <br>
                <div class="form-group">
                    <label for="pago">Aportar un pago a la deuda:</label>
                    <input type="text" class="form-control" id="pago" name="pago" placeholder="Ingrese el pago">
                </div>
                <br>
                <div class="form-group">
                    <label for="fecha">Fecha:</label>
                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                </div>
                <br>
                <button type="button" class="btn btn-dark btn-rounded" id="btn-pagar">Pagar</button>
            </form>
        </div>
        <!-- Contenedor derecho con el registro de pagos, buscadores de fecha y botones -->
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Registro de Pagos</h2>
                </div>
            </div>
            <!-- Agregar el input para buscar por rango de fechas -->
            <div class="row">
                <div class="col-md-12 mb-3">
                    <form id="date_range_form" method="post" action="registro de pagos.php">
                    <br>    
                    <div class="form-group">
                            <label for="date_range">Rango de fechas:</label>
                            <div class="input-group">
                                <input type="date" class="form-control" id="min_date" name="min_date" placeholder="Fecha mínima">
                                <div class="input-group-append">
                                    <span class="input-group-text">-</span>
                                </div>
                                <input type="date" class="form-control" id="max_date" name="max_date" placeholder="Fecha máxima">
                            </div>
                        </div>
                    </form>
                  <div class="mb-3">
                    <label for="marca" class="form-label">Marca del auto:</label>
                    <input
                      type="text"
                      class="form-control"
                      id="marca"
                      name="marca"
                      placeholder="Ingresa la marca del auto para filtrar"
                    />
                  </div>
                </div>
            </div>
            <!-- Fin del input para buscar por rango de fechas -->
            <!-- Tabla de registros existentes -->
            <div id="ventasTableContainer">
                <!-- Aquí se cargará la tabla de ventas -->
            </div>
            <div class="row">
                <div class="col-md-12 text-center mt-3">
                    <!-- Botones en el orden solicitado -->
                    <button type="button" class="btn btn-dark btn-rounded" id="btn-buscar">Buscar</button>
                    <button type="button" class="btn btn-dark btn-rounded" id="btn-imprimir-pdf">Imprimir PDF</button>
                    <button type="button" class="btn btn-dark btn-rounded" id="btn-limpiar-tabla">Limpiar</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Contenedor para mostrar las alertas -->
<div id="alert-container"></div>

<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>

<script>
    // Función para buscar autos asociados al dueño cuando se escribe en el campo de texto
    function buscarAutos() {
        var dueno = document.getElementById('dueno').value;
        var autoSelect = document.getElementById('auto');

        // Verificar si el nombre del dueño no está vacío
        if (dueno.trim() !== '') {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    console.log('Nombre del dueño:', dueno);
                    var autos = JSON.parse(this.responseText);
                    console.log('Autos recibidos:', autos);
                    autoSelect.innerHTML = ''; // Limpiar opciones anteriores
                    autos.forEach(function(auto) {
                        var option = document.createElement('option');
                        option.value = auto.idventas;
                        // Concatenar la marca, modelo, precio y año del auto para mostrar en el combobox
                        option.textContent = auto.marca + ' ' + auto.modelo + ' ' + auto.precio + ' ' + auto.anio;
                        autoSelect.appendChild(option);
                    });
                }
            };
            // Realizar la solicitud AJAX al servidor para obtener los autos asociados al dueño
            xhttp.open("GET", "obtener_autos.php?dueno=" + dueno, true);
            xhttp.send();
        } else {
            // Si el nombre del dueño está vacío, limpiar las opciones del combobox
            autoSelect.innerHTML = '';
        }
    }

    document.addEventListener("DOMContentLoaded", function () {
        // Asignar eventos y funciones aquí

        // Cargar la tabla de ventas al cargar la página
        loadVentasTable();

        // Asignar evento clic al botón "Buscar"
        document.getElementById('btn-buscar').addEventListener('click', function () {
            var minDate = document.getElementById('min_date').value;
            var maxDate = document.getElementById('max_date').value;
            var marca = document.getElementById('marca').value; // Obtener el valor del input de marca

            console.log('Fecha mínima:', minDate);
            console.log('Fecha máxima:', maxDate);
            console.log('Marca:', marca);

            // Realizar la consulta AJAX para obtener los registros de pagos dentro del rango de fechas y marca
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Actualizar la tabla de registros de pagos
                    document.getElementById("ventasTableContainer").innerHTML = this.responseText;
                }
            };
            xhttp.open("POST", "buscar_pagos.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("min_date=" + minDate + "&max_date=" + maxDate + "&marca=" + marca);
        });

        // Asignar evento clic al botón "Imprimir PDF"
        document.getElementById('btn-imprimir-pdf').addEventListener('click', function () {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Descargar el PDF generado
                    var blob = new Blob([this.response], { type: 'application/pdf' });
                    var url = window.URL.createObjectURL(blob);
                    
                    // Abrir una nueva ventana o pestaña con el PDF
                    window.open(url);
                }
            };
            xhttp.open("GET", "generar_pdf_pagos.php", true);
            xhttp.responseType = 'blob';
            xhttp.send();
        });

        // Asignar evento clic al botón "Limpiar tabla"
        document.getElementById('btn-limpiar-tabla').addEventListener('click', function () {
            // Limpiar la tabla de ventas
            document.getElementById("ventasTableContainer").innerHTML = '';
        });
    });

   // Después de la función buscarAutos()
document.getElementById('btn-pagar').addEventListener('click', function () {
    // Obtener los datos del formulario
    var dueno = document.getElementById('dueno').value;
    var auto = document.getElementById('auto').value;
    var pago = document.getElementById('pago').value;
    var fecha = document.getElementById('fecha').value;

    // Verificar si el campo "Auto" tiene un valor seleccionado
    if (auto.trim() === '') {
        console.log("Por favor, seleccione un auto.");
        return; // Salir de la función si el campo "Auto" está vacío
    }

    // Crear un objeto FormData solo con los datos del auto seleccionado
    var formData = new FormData();
    formData.append('dueno', dueno);
    formData.append('auto', auto);
    formData.append('pago', pago);
    formData.append('fecha', fecha);

    // Realizar la solicitud AJAX para registrar el pago
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Parsear la respuesta JSON del servidor
            var response = JSON.parse(this.responseText);

            // Verificar si hay alertas para mostrar
            if (response.length > 0) {
                // Mostrar las alertas en un elemento específico de tu página
                var alertContainer = document.getElementById('alert-container');
                alertContainer.innerHTML = ''; // Limpiar alertas anteriores
                response.forEach(function(alert) {
                    alertContainer.innerHTML += '<div class="alert alert-danger">' + alert + '</div>';
                });
            } else {
                // La operación se realizó correctamente, puedes hacer algo más si lo deseas
                console.log("El pago se registró correctamente.");
            }
        }
    };
    xhttp.open("POST", "registrar_pago.php", true);
    xhttp.send(formData);
});

    // Función para cargar la tabla de ventas
    function loadVentasTable() {
        // Realizar la consulta AJAX para obtener la tabla de ventas
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                // Insertar la tabla de ventas en el contenedor
                document.getElementById("ventasTableContainer").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "buscar_pagos.php", true);
        xhttp.send();
    }
</script>

</body>
</html>