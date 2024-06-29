<?php
require_once __DIR__ . '/../../includes/Database.php';

function buscarCabanaPorNumero($numero) {
    $conexion = Database::obtenerInstancia()->obtenerConexion();

    $stmt = $conexion->prepare("SELECT * FROM cabanas WHERE numero = ?");
    $stmt->execute([$numero]);
    $cabana = $stmt->fetch(PDO::FETCH_ASSOC);

    return $cabana;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Buscar Cabaña por Número</title>
</head>
<body>
    <h1>Buscar Cabaña por Número</h1>
    <form method="POST" action="buscarCabana.php">
        Número de Cabaña: <input type="text" name="numero" required><br>
        <input type="submit" value="Buscar Cabaña">
    </form>

    <?php
    // Verificar si se envió el formulario
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Obtener el número de cabaña enviado por el formulario
        $numero = $_POST['numero'];
        
        // Incluir el archivo con la función de búsqueda de cabaña por número
        require_once 'funcionalidades/cabanas/buscarcabananumero.php';
        
        // Llamar a la función para buscar la cabaña por su número
        $cabana = buscarCabanaPorNumero($numero);
        
        // Mostrar resultados según el resultado de la búsqueda
        if ($cabana) {
            // Mostrar los detalles de la cabaña encontrada
            echo "<h2>Cabaña Encontrada</h2>";
            echo "<p>Número: " . htmlspecialchars($cabana['numero']) . "</p>";
            echo "<p>Capacidad: " . htmlspecialchars($cabana['capacidad']) . "</p>";
            echo "<p>Descripción: " . htmlspecialchars($cabana['descripcion']) . "</p>";
            echo "<p>Costo Diario: $" . htmlspecialchars($cabana['costo_diario']) . "</p>";
        } else {
            // Mostrar mensaje si no se encuentra la cabaña
            echo "<p>No se encontró una cabaña con ese número.</p>";
        }
    }
    ?>
</body>
</html>
