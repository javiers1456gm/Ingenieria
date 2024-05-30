<!doctype html>
<html lang="en">
<head>
    <title>navBar</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous"
    />
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="login.php">Auto Shop Administration</a>
    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavId" aria-controls="collapsibleNavId" aria-expanded="false" aria-label="Toggle navigation"></button>
    <div class="collapse navbar-collapse" id="collapsibleNavId">
        <ul class="navbar-nav me-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="CRUD citas .php">citas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="buscarAuto.php">buscar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registro de ventas.php">Reporte de ventas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registro de pagos.php">Reporte de pagos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="CRUD de roles.php">Roles</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="CRUDusuarios.php">Usuarios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="registroclientes.php">Registro de clientes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="CRUD Registroautos4.php">Registro de autos</a>
            </li>
            
            <!-- Agregar un botón de cierre de sesión -->
            <li class="nav-item">
                <a class="nav-link" href="#" id="logoutBtn">Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</nav>

<script>
    // Manejar el evento de clic en el botón de cierre de sesión
    document.getElementById("logoutBtn").addEventListener("click", function(event) {
        event.preventDefault(); // Prevenir el comportamiento predeterminado del enlace

        // Enviar una solicitud al servidor para cerrar la sesión
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "logout.php", true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Redireccionar a la página de inicio de sesión después de cerrar la sesión
                window.location.href = "login.php";
            }
        };
        xhr.send();
    });
</script>

<!-- Bootstrap JavaScript Libraries -->
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"
></script>

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
    crossorigin="anonymous"
></script>
</body>
</html>
