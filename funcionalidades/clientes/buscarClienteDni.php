<?php
require_once __DIR__ . '/../../includes/Database.php';

function buscarClientePorDNI($dni) {
    $conexion = Database::obtenerInstancia()->obtenerConexion();

    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE dni = ?");
    $stmt->execute([$dni]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    return $cliente;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Cliente</title>
</head>
<body>
    <h1>Buscar Cliente</h1>
    <form method="POST" action="buscarClienteDni.php">
        DNI: <input type="text" name="dni" required><br>
        <input type="submit" value="Buscar Cliente">
    </form>

    <?php
    // Verificar si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dni = $_POST['dni'];
        $cliente = buscarClientePorDNI($dni);

        if ($cliente) {
            // Mostrar los detalles del cliente encontrado
            echo "<h2>Cliente Encontrado</h2>";
            echo "<p>DNI: " . htmlspecialchars($cliente['dni']) . "</p>";
            echo "<p>Nombre: " . htmlspecialchars($cliente['nombre']) . "</p>";
            echo "<p>Dirección: " . htmlspecialchars($cliente['direccion']) . "</p>";
            echo "<p>Teléfono: " . htmlspecialchars($cliente['telefono']) . "</p>";
            echo "<p>Email: " . htmlspecialchars($cliente['email']) . "</p>";
        } else {
            // Mostrar mensaje si no se encuentra el cliente
            echo "<p>No se encontró un cliente con ese DNI.</p>";
        }
    }
    ?>
</body>
</html>
