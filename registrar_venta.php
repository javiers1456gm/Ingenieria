<?php
session_start();
// Verificar si se han enviado los datos del formulario de venta
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_usuario = $_POST['id_usuario'];
    $id_cliente = $_POST['id_cliente'];
    $fecha = $_POST['fecha'];
    $total_venta = $_POST['total_venta'];
    $anticipo = $_POST['anticipo'];
    $num_pagos = $_POST['num_pagos'];
    $autos_seleccionados = $_POST['autos_seleccionados']; // Obtener los autos seleccionados del formulario

    // Conectar a la base de datos
    $conn = mysqli_connect("localhost", "root", "", "autoshop");
    if (!$conn) {
        die("La conexión a la base de datos falló: " . mysqli_connect_error());
    }

    // Consulta SQL para insertar la venta en la base de datos
    $sql = "INSERT INTO ventas (idUsuarios, idClientes, fecha_venta, total_venta, anticipo, numero_de_pagos) 
            VALUES ('$id_usuario', '$id_cliente', '$fecha', '$total_venta', '$anticipo', '$num_pagos')";

    // Ejecutar la consulta
    if (mysqli_query($conn, $sql)) {
        // Obtener el ID de la venta recién insertada
        $id_venta = mysqli_insert_id($conn);

        // Convertir los autos seleccionados en un array
        $autos_array = explode(',', $autos_seleccionados);

        // Insertar los detalles de los autos vendidos en la tabla de detalle_ventas
        foreach ($autos_array as $id_auto) {
            $sql_detalle = "INSERT INTO articulo (idventas, idautos) VALUES ('$id_venta', '$id_auto')";

            mysqli_query($conn, $sql_detalle);
        }

        // Redireccionar a alguna página de éxito o mostrar un mensaje de éxito
        header("Location: buscarAuto.php");
        exit();
    } else {
        echo "Error al registrar la venta: " . mysqli_error($conn);
    }

    // Cerrar la conexión
    mysqli_close($conn);
} else {
    // Si se intenta acceder a este script sin enviar datos de venta, redireccionar a alguna página de error
    header("Location: buscarAuto.php");

    exit();
}
?>


