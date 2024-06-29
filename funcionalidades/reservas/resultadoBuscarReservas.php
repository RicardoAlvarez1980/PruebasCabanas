<?php
require_once './includes/Database.php';
require_once './clases/Reservas.php';
require_once './funcionalidades/cabanas/buscarCabana.php'; // Archivo con funciones de búsqueda de cabanas
require_once './funcionalidades/clientes/buscarClienteDni.php'; // Archivo con funciones de búsqueda de clientes

// Inicializar variables para los resultados
$resultados = [];
$mensaje = '';

// Verificar si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener parámetros de búsqueda
    $numeroReserva = $_GET['numero_reserva'] ?? null;
    $dniCliente = $_GET['dni_cliente'] ?? null;
    $numeroCabana = $_GET['numero_cabana'] ?? null;

    // Realizar la búsqueda según el parámetro que esté presente
    if (!empty($numeroReserva)) {
        $reserva = buscarReservaPorNumero($numeroReserva);
        if ($reserva) {
            $resultados[] = $reserva;
        } else {
            $mensaje = "No se encontró una reserva con ese número.";
        }
    } elseif (!empty($dniCliente)) {
        $reservas = buscarReservasPorCliente($dniCliente);
        if ($reservas) {
            $resultados = $reservas;
        } else {
            $mensaje = "No se encontraron reservas para el cliente con ese DNI.";
        }
    } elseif (!empty($numeroCabana)) {
        $reservas = buscarReservasPorCabana($numeroCabana);
        if ($reservas) {
            $resultados = $reservas;
        } else {
            $mensaje = "No se encontraron reservas para la cabaña con ese número.";
        }
    } else {
        $mensaje = "Ingrese al menos uno de los criterios de búsqueda.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resultados de Búsqueda de Reservas</title>
</head>

<body>
    <h1>Resultados de Búsqueda de Reservas</h1>

    <!-- Formulario de Búsqueda -->
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="numero_reserva">Número de Reserva:</label>
        <input type="text" id="numero_reserva" name="numero_reserva" value="<?php echo htmlspecialchars($numeroReserva ?? ''); ?>"><br><br>

        <label for="dni_cliente">DNI del Cliente:</label>
        <input type="text" id="dni_cliente" name="dni_cliente" value="<?php echo htmlspecialchars($dniCliente ?? ''); ?>"><br><br>

        <label for="numero_cabana">Número de Cabaña:</label>
        <input type="text" id="numero_cabana" name="numero_cabana" value="<?php echo htmlspecialchars($numeroCabana ?? ''); ?>"><br><br>

        <input type="submit" value="Buscar Reserva">
    </form>

    <!-- Mostrar resultados -->
    <?php if (!empty($resultados)) : ?>
        <h2>Resultados:</h2>
        <ul>
            <?php foreach ($resultados as $reserva) : ?>
                <li>
                    Número de Reserva: <?php echo htmlspecialchars($reserva->getNumero()); ?><br>
                    Fecha de Inicio: <?php echo htmlspecialchars($reserva->getFechaInicio()); ?><br>
                    Fecha de Fin: <?php echo htmlspecialchars($reserva->getFechaFin()); ?><br>
                    DNI Cliente: <?php echo htmlspecialchars($cliente->getDni()); ?><br>
                    Número de Cabaña: <?php echo htmlspecialchars($cabana->getNumero()); ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php elseif (!empty($mensaje)) : ?>
        <p><?php echo htmlspecialchars($mensaje); ?></p>
    <?php endif; ?>
</body>

</html>