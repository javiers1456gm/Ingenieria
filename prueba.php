<!-- Botón único para mostrar todos los formularios -->
<div class="row justify-content-center mt-3">
    <button id="mostrarFormulario" type="submit" class="btn btn-dark">Pagar</button>
</div>
<!-- Formularios (inicialmente ocultos) -->
<div id="formularioCarroAutos" style="display: none;">
    <!-- Aquí va el contenido del formulario de carroAutos -->
    <!-- Ejemplo de formulario básico -->
    <div class="row justify-content-center mt-3">
        <h3>Formulario de Carro Autos</h3>
    </div>
    <div class="container mt-5">
        <!-- Formulario de registro de venta -->
        <form id="registroVentaForm" method="POST" action="registrar_venta.php">
            <!-- Resto del formulario -->
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
    <!-- Formulario para validar el usuario y contraseña -->
    <div id="validarUsuario" style="display: none;">
        <h3>Validar Usuario</h3>
        <form action="validar_usuario.php" method="post">
            <div class="form-group">
                <label for="usuario">Usuario:</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
            </div>
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <input type="password" class="form-control" id="contrasena" name="contrasena" required>
            </div>
            <button type="submit" class="btn btn-primary">Validar</button>
        </form>
    </div>

    <!-- Formulario para llenar los datos del cliente -->
    <div id="llenarCliente" style="display: none;">
        <h3>Llenar Datos del Cliente</h3>
        <form id="llenarClienteForm" method="post">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido:</label>
                <input type="text" class="form-control" id="apellido" name="apellido" required>
            </div>
            <div class="form-group">
                <label for="telefono">Teléfono:</label>
                <input type="text" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
        </form>
    </div>

</div>
