<?php
require_once __DIR__ . '/../../includes/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];

    $conexion = Database::obtenerInstancia()->obtenerConexion(); // Obtener la conexión directamente
    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE nombre LIKE ?");
    $stmt->execute(['%' . $nombre . '%']);
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buscar Cliente</title>
</head>
<body>
    <h1>Buscar Cliente</h1>
    <form method="POST" action="buscarCliente.php">
        Nombre: <input type="text" name="nombre" required><br>
        <input type="submit" value="Buscar Cliente">
    </form>

    <?php if (isset($clientes)): ?>
        <?php if (count($clientes) > 0): ?>
            <h2>Clientes Encontrados</h2>
            <table border="1">
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                </tr>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cliente['dni']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['direccion']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['telefono']); ?></td>
                        <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>No se encontraron clientes con ese nombre.</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
