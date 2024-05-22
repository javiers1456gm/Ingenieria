<?php
$conn = new mysqli("localhost", "root", "", "autoshop");
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "SELECT nombre_rol, descripcion_rol FROM roles WHERE idroles = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $rol = $result->fetch_assoc();
        echo json_encode($rol);
    } else {
        echo json_encode(array("error" => "No se encontró ningún rol con el ID proporcionado."));
    }
} else {
    echo json_encode(array("error" => "ID de rol no válido."));
}

$stmt->close();
$conn->close();
?>
