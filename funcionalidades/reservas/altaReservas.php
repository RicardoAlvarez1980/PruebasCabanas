<?php
require_once './clases/Cabanas.php';
require_once './clases/Reservas.php';
require_once './clases/Clientes.php';
require_once __DIR__ . '/../../includes/Database.php';

// Variable para almacenar las reservas (simulación en memoria)
$reservas = [];  // Inicializamos el array vacío

// Función para buscar un cliente por su DNI
function buscarClientePorDNI($dni) {
    $conexion = Database::obtenerInstancia()->obtenerConexion();
    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE dni = ?");
    $stmt->execute([$dni]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para buscar una cabaña por su número
function buscarCabanaPorNumero($numero) {
    $conexion = Database::obtenerInstancia()->obtenerConexion();
    $stmt = $conexion->prepare("SELECT * FROM cabanas WHERE numero = ?");
    $stmt->execute([$numero]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Función para validar disponibilidad de una cabaña en las fechas seleccionadas
function validarDisponibilidadCabana($numeroCabana, $fechaInicio, $fechaFin) {
    $conexion = Database::obtenerInstancia()->obtenerConexion();

    // Consulta para verificar si hay reservas que colisionen en las fechas seleccionadas
    $stmt = $conexion->prepare("SELECT COUNT(*) AS count FROM reservas WHERE cabana_numero = ? AND ((fecha_inicio BETWEEN ? AND ?) OR (fecha_fin BETWEEN ? AND ?))");
    $stmt->execute([$numeroCabana, $fechaInicio, $fechaFin, $fechaInicio, $fechaFin]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si count es mayor a 0, significa que hay al menos una reserva que colisiona en esas fechas
    return intval($result['count']) === 0;
}

// Función para realizar el alta de una reserva
function altaReserva() {
    global $reservas;  // Accedemos a la variable global $reservas

    // Obtener datos del formulario o de la solicitud web
    $dniCliente = $_POST['dni_cliente'] ?? null;
    $numeroCabana = $_POST['numero_cabana'] ?? null;
    $fechaInicio = $_POST['fecha_inicio'] ?? null;
    $fechaFin = $_POST['fecha_fin'] ?? null;

    // Validar que todos los datos requeridos estén presentes
    if (!$dniCliente || !$numeroCabana || !$fechaInicio || !$fechaFin) {
        echo "Faltan datos requeridos para completar la reserva.\n";
        return;
    }

    // Buscar el cliente por su DNI
    $clienteSeleccionado = buscarClientePorDNI($dniCliente);

    if (!$clienteSeleccionado) {
        echo "No se encontró un cliente con ese DNI. La reserva no se puede completar.\n";
        return;
    }

    // Buscar la cabaña por su número
    $cabanaSeleccionada = buscarCabanaPorNumero($numeroCabana);

    if (!$cabanaSeleccionada) {
        echo "No se encontró una cabaña con ese número. La reserva no se puede completar.\n";
        return;
    }

    // Validar disponibilidad de la cabaña en las fechas seleccionadas
    if (!validarDisponibilidadCabana($numeroCabana, $fechaInicio, $fechaFin)) {
        echo "La cabaña seleccionada no está disponible en las fechas especificadas. Por favor, elige otras fechas o cabaña.\n";
        return;
    }

    // Crear una nueva instancia de Reservas con los datos proporcionados
    $reserva = new Reservas(count($reservas) + 1, $fechaInicio, $fechaFin, $clienteSeleccionado, $cabanaSeleccionada);
    $reservas[] = $reserva;

    // Insertar la reserva en la base de datos
    $conexion = Database::obtenerInstancia(); // Obtenemos una instancia de la conexión
    $pdo = $conexion->obtenerConexion();

    // Preparar la consulta SQL para insertar la reserva en la base de datos
    $stmt = $pdo->prepare("INSERT INTO reservas (fecha_inicio, fecha_fin, cliente_dni, cabana_numero) VALUES (?, ?, ?, ?)");

    // Ejecutar la consulta con los datos de la reserva
    $stmt->execute([$fechaInicio, $fechaFin, $dniCliente, $numeroCabana]);

    echo "Reserva agregada exitosamente.\n";
}

// Ejecutar la función altaReserva si se ha enviado el formulario de reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    altaReserva();
}
?>