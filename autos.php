<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Autos</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Registro de Autos</h1>
        
        <?php
        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "autoshop";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        // Insertar un nuevo auto
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $concesion = $_POST['concesion'];
            $marca = $_POST['marca'];
            $modelo = $_POST['modelo'];
            $precio = $_POST['precio'];
            $año = $_POST['ano'];
            $dueño_id = $_POST['dueño'];

            $sql = "INSERT INTO autos (concesion, marca, modelo, precio, año, dueño_id)
                    VALUES ('$concesion', '$marca', '$modelo', '$precio', '$año', '$dueño_id')";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success mt-3' role='alert'>Auto agregado exitosamente.</div>";
            } else {
                echo "<div class='alert alert-danger mt-3' role='alert'>Error al agregar auto: " . $conn->error . "</div>";
            }
        }

        // Mostrar formulario para agregar un nuevo auto
        ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="mt-3">
        <div class="form-group">
                <label for="concesion">Tipo de Concesión:</label>
                <select class="form-control" id="concesion" name="concesion" required>
                    <option value="Dueño">Dueño</option>
                    <option value="Propio">Propio</option>
            </select>
            <div class="form-group">
                <label for="marca">Marca:</label>
                <input type="text" class="form-control" id="marca" name="marca" required>
            </div>
            <div class="form-group">
                <label for="modelo">Modelo:</label>
                <input type="text" class="form-control" id="modelo" name="modelo" required>
            </div>
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" class="form-control" id="precio" name="precio" required>
            </div>
            <div class="form-group">
                <label for="ano">Año:</label>
                <input type="number" class="form-control" id="ano" name="ano" required>
            </div>
            <div class="form-group">
                <label for="dueño">Dueño:</label>
                <select class="form-control" id="dueño" name="dueño" required>
                    <?php
                    $sql = "SELECT id, nombre FROM dueños";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-dark">Agregar Auto</button>
        </form>

        <?php
        // Mostrar la lista de autos
        $sql = "SELECT autos.id, autos.concesion, autos.marca, autos.modelo, autos.precio, autos.año, dueños.nombre AS dueño 
                FROM autos 
                INNER JOIN dueños ON autos.dueño_id = dueños.id";
        $result = $conn->query($sql);

        

        $conn->close();
        ?>
    </div>
</body>
</html>