<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login con Bootstrap 5</title>
  <style>
    /* Establece la imagen de fondo */
    body {
      background-image: url('fondo login.jpg'); /* Ruta relativa de la imagen */
      /* Ajusta el tamaño de la imagen de fondo para cubrir toda la pantalla */
      background-size: cover;
      /* Centra la imagen de fondo */
      background-position: center;
      /* Fija la imagen de fondo para que no se desplace con el contenido */
      background-attachment: fixed;
    }
  </style>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
 
</head>
<body>
    <form method="POST" action="validar.php">
<!-- Contenido de tu página -->
<br>
<br>
<br>

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card border-0"> <!-- Quita la línea de contorno de la tarjeta -->
        <h5 class="card text-right border-0"> <!-- Quita la línea de contorno -->
          <strong>Auto Shop Administration</strong>
        </h5>
        <h6 style="color: grey;">Bienvenido</h6>
        <div class="card-body">
          <h5 class="card-title text-center">Inicio de Sesión</h5>
          <!-- Formulario de inicio de sesión -->
          <form>
            <div class="mb-3">
              <label for="inputEmail" class="form-label">Usuario</label>
              <input placeholder="Escribe tu usuario" type="text" class="form-control border-0" id="nombre" name="nombre">

            </div>
            <div class="mb-3">
              <label for="inputPassword" class="form-label" >Contraseña</label>
              <input placeholder="Escribe tu contraseña" type="password" class="form-control border-0" id="contrasena" name="contrasena"> <!-- Quita la línea de contorno del input -->
            </div>
            <div class="d-grid gap-2">
              <button type="submit" class="btn btn-dark btn-block">Entrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JavaScript (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</form>
</body>
</html>