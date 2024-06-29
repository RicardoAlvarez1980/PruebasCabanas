<?php
require_once __DIR__ . '/../../includes/Database.php';
require_once './clases/Cabanas.php';
require_once './clases/Reservas.php';
require_once './clases/Clientes.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación y procesamiento del formulario
    if (isset($_POST['dni'], $_POST['nombre'], $_POST['direccion'], $_POST['telefono'], $_POST['email'])) {
        $dni = $_POST['dni'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];

        // Insertar datos en la base de datos
        $conexion = Database::obtenerInstancia()->obtenerConexion();
        $stmt = $conexion->prepare("INSERT INTO clientes (dni, nombre, direccion, telefono, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$dni, $nombre, $direccion, $telefono, $email]);

        echo "Cliente agregado exitosamente.";
    } else {
        echo "Faltan datos obligatorios.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alta de Cliente</title>
</head>
<body>
    <h1>Alta de Cliente</h1>
    <form method="POST" action="./funcionalidades/clientes/altaClientes.php">
        DNI: <input type="text" name="dni" required><br>
        Nombre: <input type="text" name="nombre" required><br>
        Dirección: <input type="text" name="direccion" required><br>
        Teléfono: <input type="text" name="telefono" required><br>
        Email: <input type="email" name="email" required><br>
        <input type="submit" value="Agregar Cliente">
    </form>
</body>
</html>
