<?php
require_once __DIR__ . '/../../includes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];

    $conexion = Database::obtenerInstancia()->obtenerConexion();

    $stmt = $conexion->prepare("DELETE FROM clientes WHERE dni = ?");
    $stmt->execute([$dni]);

    echo "Cliente eliminado exitosamente.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Cliente</title>
</head>
<body>
    <h1>Eliminar Cliente</h1>
    <form method="POST" action="eliminarCliente.php">
        DNI: <input type="text" name="dni" required><br>
        <input type="submit" value="Eliminar Cliente">
    </form>
</body>
</html>
