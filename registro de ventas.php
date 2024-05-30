<!DOCTYPE html>
<html lang="en">

<head>
    <title>Reporte de Ventas</title>
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
    <?php require 'navBar.php'; ?>
</head>

<body>

    <form action="obtener_registros.php" method="post">
        <div class="container" style="margin-top: 70px;">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="text-center">Registro de Ventas</h2>
                </div>
            </div>
            <!-- Rango de fechas -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="date_range">Rango de fechas:</label>
                    <div class="input-group">
                        <input type="date" class="form-control" id="min_date" name="min_date" placeholder="Fecha mínima">
                        <div class="input-group-append">
                            <span class="input-group-text">-</span>
                        </div>
                        <input type="date" class="form-control" id="max_date" name="max_date" placeholder="Fecha máxima">
                    </div>
                </div>
            </div>

            <!-- Tabla de registros de ventas -->
            <div id="ventasTableContainer">
                <!-- Aquí se cargará la tabla de ventas -->
            </div>

            <!-- Botones -->
            <div class="row">
                <div class="col-md-12 text-center mt-3">
                    <button type="button" class="btn btn-dark btn-rounded" id="btn-buscar">Buscar</button>
                    <button type="button" class="btn btn-dark btn-rounded" id="btn-imprimir-pdf">Imprimir PDF</button>
                    <button type="button" class="btn btn-dark btn-rounded" id="btn-limpiar-tabla">Limpiar</button>
                </div>
            </div>
        </div>
    </form>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Manejar evento clic en el botón "Buscar"
            document.getElementById('btn-buscar').addEventListener('click', function () {
                // Obtener los valores de las fechas
                var minDate = document.getElementById('min_date').value;
                var maxDate = document.getElementById('max_date').value;

                // Validar que se hayan ingresado ambas fechas
                if (minDate && maxDate) {
                    // Ejecutar la consulta con AJAX
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            // Actualizar la tabla con los resultados de la consulta
                            document.getElementById("ventasTableContainer").innerHTML = this.responseText;
                        }
                    };
                    xhttp.open("POST", "buscar_ventas.php", true);
                    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhttp.send("min_date=" + minDate + "&max_date=" + maxDate);
                } else {
                    alert("Por favor, ingrese ambas fechas para realizar la búsqueda.");
                }
            });

            // Manejar evento clic en el botón "Imprimir PDF"
            var url; // Declarar url fuera de la función de manejo de eventos

document.getElementById('btn-imprimir-pdf').addEventListener('click', function () {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Descargar el PDF generado
            var blob = new Blob([this.response], { type: 'application/pdf' });
            url = window.URL.createObjectURL(blob); // Asignar el valor a url
            
            // Abrir una nueva ventana o pestaña con el PDF
            window.open(url);
        }
    };
    xhttp.open("GET", "generar_pdf.php", true);
    xhttp.responseType = 'blob';
    xhttp.send();
});

            // Manejar evento clic en el botón "Limpiar"
            document.getElementById('btn-limpiar-tabla').addEventListener('click', function () {
                // Limpiar la tabla de ventas
                document.getElementById("ventasTableContainer").innerHTML = '';
            });
        });
    </script>

</body>

</html>
