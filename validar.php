<?php
session_start();

// Obtener los datos enviados por el formulario
$nombre_usuario = $_POST['nombre'];
$contrasena = $_POST['contrasena'];

// Conectar a la base de datos
$conn = mysqli_connect("localhost", "root", "", "autoshop");

// Verificar la conexión
if (!$conn) {
    die("La conexión a la base de datos ha fallado: " . mysqli_connect_error());
}

// Consulta preparada para evitar la inyección de SQL
$consulta = "SELECT idUsuarios, nombre_vendedor, apellido_paterno, apellido_materno, contrasena FROM usuarios WHERE nombre_vendedor=?";
$stmt = mysqli_prepare($conn, $consulta);
mysqli_stmt_bind_param($stmt, "s", $nombre_usuario);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

// Verificar si se encontraron filas
if (mysqli_stmt_num_rows($stmt) > 0) {
    mysqli_stmt_bind_result($stmt, $id_usuario, $nombre_vendedor, $apellido_paterno, $apellido_materno, $contrasena_hash);

    // Iterar sobre los resultados
    while (mysqli_stmt_fetch($stmt)) {
        // Verificar la contraseña utilizando password_verify
        if (password_verify($contrasena, $contrasena_hash)) {
            // Construir el nombre completo del usuario
            $nombre_completo = $nombre_vendedor . " " . $apellido_paterno . " " . $apellido_materno;

            // Guardar el ID de usuario y el nombre completo en la sesión
            $_SESSION['idUsuarios'] = $id_usuario;
            $_SESSION['nombre_completo'] = $nombre_completo; // Guardar el nombre completo del usuario en la sesión
            
            // Redireccionar a la página buscarAuto.php
            header("Location: buscarAuto.php");
            exit; // Asegurarse de que el script se detenga después de la redirección
        }
    }

    // Si ninguna contraseña coincide
    include("login.php");
    echo "<center><h2 class='bad'>ERROR EN LA AUTENTICACION</h2></center>";
} else {
    // Si no se encuentra ningún usuario con ese nombre
    include("login.php");
    echo "<center><h2 class='bad'>ERROR EN LA AUTENTICACION</h2></center>";
}

// Cerrar la conexión y liberar los recursos
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
