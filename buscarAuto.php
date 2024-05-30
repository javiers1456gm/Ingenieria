<!-- Agregamos una variable PHP para almacenar los precios de los autos seleccionados -->
<?php
$total_ventas = 0; // Inicializamos el total de ventas en 0
session_start();
?>
<?php
require 'navBar.php';
$conn = mysqli_connect("localhost", "root", "", "autoshop");
if (!$conn) {
    die("La conexión a la base de datos falló: " . mysqli_connect_error());
}

// Procesar los datos del formulario si se envió
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inicializamos las variables de búsqueda
    $marca = "";
    $modelo = "";
    $min_price = 0;
    $max_price = PHP_INT_MAX;
    $min_year = 0;
    $max_year = PHP_INT_MAX;

    // Verificamos si se han proporcionado valores en los campos del formulario
    if (!empty($_POST["marca"])) {
        $marca = $_POST["marca"];
    }
    if (!empty($_POST["modelo"])) {
        $modelo = $_POST["modelo"];
    }
    if (!empty($_POST["min_price"]) && is_numeric($_POST["min_price"])) {
        $min_price = $_POST["min_price"];
    }
    if (!empty($_POST["max_price"]) && is_numeric($_POST["max_price"])) {
        $max_price = $_POST["max_price"];
    }
    if (!empty($_POST["min_year"]) && is_numeric($_POST["min_year"])) {
        $min_year = $_POST["min_year"];
    }
    if (!empty($_POST["max_year"]) && is_numeric($_POST["max_year"])) {
        $max_year = $_POST["max_year"];
    }

// Construimos la consulta SQL para obtener los autos y sus fotos relacionadas
$sql = "SELECT autos.*, fotos_auto.foto 
        FROM autos 
        LEFT JOIN fotos_auto ON autos.idautos = fotos_auto.idautos 
        WHERE marca LIKE '%$marca%'";

// Si se proporciona un modelo, agregamos la condición a la consulta SQL
if (!empty($modelo)) {
    $sql .= " AND modelo LIKE '%$modelo%'";
}

// Agregar condiciones para precio mínimo y máximo, y año mínimo y máximo
if ($min_price > 0 && $max_price > 0) {
    $sql .= " AND precio BETWEEN $min_price AND $max_price";
}
if ($min_year > 0 && $max_year > 0) {
    $sql .= " AND anio BETWEEN $min_year AND $max_year";
}

// Agregar condición para autos no vendidos
$sql .= " AND autos.idautos NOT IN (SELECT idautos FROM articulo)";

// Depurar: Mostrar la consulta SQL por consola
echo "<script>console.log('Consulta SQL:', \"$sql\");</script>";

// Ejecutamos la consulta
$result = mysqli_query($conn, $sql);

// Verificar si hubo errores en la consulta
if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn));
}


// Almacenar los resultados y las fotos asociadas en un array
$autos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $autoId = $row['idautos'];
    if (!isset($autos[$autoId])) {
        $autos[$autoId] = $row;
        $autos[$autoId]['fotos'] = [];
    }
    if (!empty($row['foto'])) {
        $autos[$autoId]['fotos'][] = $row['foto'];
    }
}
}
// Realizar la consulta SQL para obtener los clientes
$sql_clientes = "SELECT * FROM clientes";
$result_clientes = mysqli_query($conn, $sql_clientes);

// Verificar si la consulta fue exitosa
if ($result_clientes) {
    // Inicializar el array para almacenar los datos de los clientes
    $clientes = [];

    // Recorrer los resultados y almacenarlos en el array $clientes
    while ($row_cliente = mysqli_fetch_assoc($result_clientes)) {
        $clientes[] = $row_cliente;
    }
} else {
    // Manejar el caso de error en la consulta
    echo "Error al obtener los clientes: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar auto</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .center-content {
            margin: 0 auto; /* Centra horizontalmente el contenido */
            max-width: 600px; /* Establece un ancho máximo para el contenido */
        }
        .space-between {
            margin-top: 20px; /* Agrega espacio superior */
            margin-bottom: 20px; /* Agrega espacio inferior */
        }
        .card {
            margin-top: 20px; /* Agrega espacio superior */
        }
        .results-text {
            margin-right: 10px; /* Agrega espacio a la derecha del texto */
        }
        .btn-selected {
            background-color: black !important;
        }
    </style>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-7 center-content">
            <h2 class="text-center">Buscar auto</h2>
            <form action="" method="post" enctype="multipart/form-data"> <!-- Action vacío para enviar los datos del formulario a la misma página -->
                <!-- Resto del formulario -->
                <div class="form-group">
                    <label for="marca">Marca:</label>
                    <input type="text" class="form-control" id="marca" name="marca" placeholder="Ingrese la marca del auto">
                </div>
                <div class="form-group">
                    <label for="modelo">Modelo:</label>
                    <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Ingrese el modelo del auto">
                </div>
                <div class="form-group">
                    <label for="price_range">Rango de precios:</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Precio mínimo">
                        <div class="input-group-append">
                            <span class="input-group-text">-</span>
                        </div>
                        <input type="number" class="form-control" id="max_price" name="max_price" placeholder="Precio máximo">
                    </div>
                </div>
                <div class="form-group">
                    <label for="year_range">Rango de años:</label>
                    <div class="input-group">
                        <input type="number" class="form-control" id="min_year" name="min_year" placeholder="Año mínimo">
                        <div class="input-group-append">
                            <span class="input-group-text">-</span>
                        </div>
                        <input type="number" class="form-control" id="max_year" name="max_year" placeholder="Año máximo">
                    </div>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-dark">Buscar</button>
                </div>
            </form>
        </div>
    </div>
    
  <div class="row">
        <?php
        if (isset($autos) && count($autos) > 0) {
            foreach ($autos as $auto) {
                ?>
                <div class="col-md-4">
                    <div class="card">
                        <!-- Contenido de la tarjeta del auto -->
                        <div class="card">
    <div id="carouselExampleControls<?php echo $auto['idautos']; ?>" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <?php
            foreach ($auto['fotos'] as $index => $foto) {
                ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img class="d-block w-100" src="<?php echo $foto; ?>" alt="Slide <?php echo $index; ?>">
                </div>
                <?php
            }
            ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls<?php echo $auto['idautos']; ?>" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls<?php echo $auto['idautos']; ?>" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <div class="card-body">
        <h5 class="card-title"><?php echo $auto["marca"] . " " . $auto["modelo"]; ?></h5>
        <p class="card-text">Año: <?php echo $auto["anio"]; ?>, Precio: $<?php echo $auto["precio"]; ?></p>
    </div>
    <form action="" method="post">
        <div class="form-group text-center">
            <button type="button" class="btn btn-primary" onclick="guardarIdAuto(<?php echo $auto['idautos']; ?>, <?php echo $auto['precio']; ?>, '<?php echo $auto['marca']; ?>', <?php echo $auto['anio']; ?>)">Seleccionar</button>
            <input type="hidden" name="autos_seleccionados[]" value="<?php echo $auto['idautos']; ?>">
        </div>
    </form>
</div>

                    </div>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="col-md-12">
                <p>No se encontraron resultados.</p>
            </div>
            <?php
        }
        ?>
    </div>

 <!-- Botón de pagar al final -->
 <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary btn-block mt-4" onclick="mostrarClientes()">Pagar</button>
        </div>
    </div>
    <form id="formBuscarCliente" method="get">  
   <!-- Tabla de clientes (inicialmente oculta) -->
<div class="row mt-4" id="tablaClientes" style="display: none;">
    <div class="container">
        <h2 class="mt-5">Buscar y Seleccionar Cliente</h2>
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
                                    <button type="button" class="btn btn-primary seleccionar-cliente">Seleccionar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</form>



<script>
    // Añadir evento click a los botones de selección de cliente
    document.querySelectorAll('.seleccionar-cliente').forEach(button => {
        button.addEventListener('click', function() {
            // Obtener el ID del cliente desde el campo oculto en el formulario
            const clienteId = this.parentNode.querySelector('input[name="cliente_id"]').value;
            // Aquí puedes hacer lo que necesites con el ID del cliente, como enviarlo a una función o almacenarlo en una variable
            console.log('ID del cliente seleccionado:', clienteId);
            // Opcionalmente, puedes realizar otras acciones aquí, como cerrar el modal o actualizar la interfaz de usuario
        });
    });
</script>

<script>
     // Agrega un controlador de eventos para el evento submit del formulario
     document.getElementById("formBuscarCliente").addEventListener("submit", function(event) {
        // Evita que el formulario se envíe de manera predeterminada (recargando la página)
        event.preventDefault();

        // Obtiene los datos del formulario
        var formData = new FormData(this);

        // Envía una solicitud AJAX al servidor
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "buscar_clientes.php?" + new URLSearchParams(formData).toString(), true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                // Procesa la respuesta del servidor y actualiza la página según sea necesario
                // Por ejemplo, puedes mostrar los resultados de la búsqueda sin recargar la página
                console.log(xhr.responseText);

                // Supongamos que el servidor devuelve los datos en formato JSON
                var clientes = JSON.parse(xhr.responseText);

                // Selecciona el cuerpo de la tabla
                var tbody = document.querySelector("#tablaClientes tbody");

                // Vacía el contenido actual del cuerpo de la tabla
                tbody.innerHTML = "";

                // Itera sobre los datos de los clientes y crea nuevas filas para la tabla
                clientes.forEach(function(cliente) {
                    var row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${cliente.nombre_cliente}</td>
                        <td>${cliente.apellido_paterno_cl}</td>
                        <td>${cliente.apellido_materno_cl}</td>
                        <td>${cliente.correo_electronico}</td>
                        <td>${cliente.telefono}</td>
                        <td>${cliente.domicilio}</td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="cliente_id" value="${cliente.idClientes}">
                                <button type="button" class="btn btn-primary seleccionar-cliente">Seleccionar</button>
                            </form>
                        </td>
                    `;

                    tbody.appendChild(row);
                });

                // Añadir nuevamente los eventos de clic a los botones de selección de cliente
                document.querySelectorAll('.seleccionar-cliente').forEach(button => {
                    button.addEventListener('click', function() {
                        const clienteId = this.parentNode.querySelector('input[name="cliente_id"]').value;
                        const nombreCliente = this.closest('tr').querySelector('td:first-child').textContent;
                        document.getElementById('id_cliente').value = clienteId;
                        document.getElementById('nombre_cliente').value = nombreCliente;
                        console.log('Cliente seleccionado:', nombreCliente);
                    });
                });
            } else {
                console.error("Error al buscar cliente:", xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error("Error de red al buscar cliente.");
        };
        xhr.send();
    });
</script>



<!-- Formulario para registrar la venta (inicialmente oculto) -->
<div class="row mt-4" id="formularioVenta" style="display: none;">
<form action="registrar_venta.php" method="post" onsubmit="return validarAnticipo()">
    <div class="col-md-12">
        <h4>Registrar venta</h4>
        <form action="registrar_venta.php" method="post">
            <!-- Mostrar el nombre del usuario -->
            <div class="form-group">
                <label for="nombre_usuario">Nombre del Usuario:</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?php echo isset($_SESSION['nombre_completo']) ? $_SESSION['nombre_completo'] : ''; ?>" readonly>
                <!-- Campo oculto para almacenar el ID del usuario -->
                <input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo isset($_SESSION['idUsuarios']) ? $_SESSION['idUsuarios'] : ''; ?>" required>
            </div>
            <!-- Mostrar el nombre del cliente -->
            <div class="form-group">
                <label for="nombre_cliente">Nombre del Cliente:</label>
                <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" readonly>
            </div>
            <!-- Campo oculto para almacenar el ID del cliente -->
            <input type="hidden" id="id_cliente" name="id_cliente" required>
            <!-- Campo oculto para almacenar los IDs de los autos seleccionados -->
            <input type="hidden" id="autos_seleccionados" name="autos_seleccionados">
            <!-- Otros campos del formulario -->
            <div class="form-group">
                <label for="fecha">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label for="total_venta">Total de la venta:</label>
                <input type="number" class="form-control" id="total_venta" name="total_venta" required>
            </div>
            <div class="form-group">
                <label for="anticipo">Anticipo:</label>
                <input type="number" class="form-control" id="anticipo" name="anticipo" required>
            </div>
            <div class="form-group">
                <label for="num_pagos">Número de pagos:</label>
                <input type="number" class="form-control" id="num_pagos" name="num_pagos" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar venta</button>
        </form>
    </div>
</div>

<!-- Sección para mostrar los autos seleccionados -->
<div class="row mt-4" id="autosSeleccionados">
    <div class="col-md-12">
        <h4>Autos Seleccionados</h4>
        <div class="row" id="autosSeleccionadosContainer">
            <!-- Aquí se mostrarán las tarjetas de los autos seleccionados -->
        </div>
    </div>
</div>
</form>

<script>
    // Añadir evento click a los botones de selección de cliente
    document.querySelectorAll('.seleccionar-cliente').forEach(button => {
        button.addEventListener('click', function() {
            const clienteId = this.parentNode.querySelector('input[name="cliente_id"]').value;
            const nombreCliente = this.closest('tr').querySelector('td:first-child').textContent;
            document.getElementById('id_cliente').value = clienteId;
            document.getElementById('nombre_cliente').value = nombreCliente;
            console.log('Cliente seleccionado:', nombreCliente);
        });
    });
</script>

<script>
    function mostrarClientes() {
        document.getElementById("tablaClientes").style.display = "block";
        document.getElementById("formularioVenta").style.display = "block";
    }

    function seleccionarCliente(idCliente, nombreCliente) {
        // Establecer el ID del cliente seleccionado en el campo correspondiente del formulario de venta
        $("#id_cliente").val(idCliente);
        // También puedes mostrar el nombre del cliente en algún lugar si es necesario
        console.log("Cliente seleccionado:", nombreCliente);
    }
</script>

<script>
   var idAutosSeleccionados = [];

   function guardarIdAuto(idAuto, precioAuto, marca, anio) {
    if (!idAutosSeleccionados.includes(idAuto)) {
        idAutosSeleccionados.push(idAuto);
        console.log("IDs de autos seleccionados:", idAutosSeleccionados);

        var totalVenta = parseFloat($("#total_venta").val()) || 0;
        totalVenta += parseFloat(precioAuto);
        $("#total_venta").val(totalVenta.toFixed(2));

        var card = `
            <div class="col-md-12 mt-2" id="auto-${idAuto}">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">${marca} ${anio}</h5>
                        <p class="card-text">Precio: $${precioAuto}</p>
                        <button type="button" class="btn btn-danger" onclick="eliminarAuto(${idAuto}, ${precioAuto})">Eliminar</button>
                    </div>
                </div>
            </div>
        `;
        $("#autosSeleccionadosContainer").append(card);

        $("#autos_seleccionados").val(idAutosSeleccionados.join(","));
    } else {
        console.log("El auto ya ha sido seleccionado.");
    }
}


function eliminarAuto(idAuto, precioAuto) {
    $("#auto-" + idAuto).remove();

    var index = idAutosSeleccionados.indexOf(idAuto);
    idAutosSeleccionados.splice(index, 1);

    console.log("Auto eliminado:", idAuto);

    var totalVenta = parseFloat($("#total_venta").val()) || 0;
    totalVenta -= parseFloat(precioAuto);
    $("#total_venta").val(totalVenta.toFixed(2));

    $("#autos_seleccionados").val(idAutosSeleccionados.join(","));
}

function validarAnticipo() {
    var totalVenta = parseFloat(document.getElementById("total_venta").value);
    var anticipo = parseFloat(document.getElementById("anticipo").value);

    if (anticipo > totalVenta) {
        alert("El anticipo no puede ser mayor que el total de la venta.");
        return false; // Evita que el formulario se envíe
    }

    // Mostrar alerta de venta registrada
    alert("¡La venta ha sido registrada exitosamente!");

    return true; // Permite que el formulario se envíe
}

</script>

<!-- Agregar un script de JavaScript para imprimir el ID de usuario en la consola -->
<script>
    console.log("ID de usuario almacenado en la sesión:", <?php echo isset($_SESSION['idUsuarios']) ? $_SESSION['idUsuarios'] : 'null'; ?>);
    console.log("Nombre completo almacenado en la sesión:", <?php echo isset($_SESSION['nombre_completo']) ? json_encode($_SESSION['nombre_completo']) : 'null'; ?>);
</script>



</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>