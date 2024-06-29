<?php
require_once __DIR__ . '/../../includes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    $conexion = Database::obtenerInstancia()->obtenerConexion();

    $stmt = $conexion->prepare("UPDATE clientes SET nombre=?, direccion=?, telefono=?, email=? WHERE dni=?");
    $stmt->execute([$nombre, $direccion, $telefono, $email, $dni]);

    echo "Cliente modificado exitosamente.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Cliente</title>
</head>
<body>
    <h1>Modificar Cliente</h1>
    <form method="POST" action="modificarCliente.php">
        DNI: <input type="text" name="dni" required><br>
        Nombre: <input type="text" name="nombre"><br>
        Dirección: <input type="text" name="direccion"><br>
        Teléfono: <input type="text" name="telefono"><br>
        Email: <input type="email" name="email"><br>
        <input type="submit" value="Modificar Cliente">
    </form>
</body>
</html>
