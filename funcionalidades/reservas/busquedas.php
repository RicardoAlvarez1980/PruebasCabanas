<?php

require_once './clases/Reservas.php';
require_once __DIR__ . '/../../includes/Database.php';

// Función para buscar reservas por número de reserva
function buscarReservaPorNumero($numero)
{
    $conexion = Database::obtenerInstancia()->obtenerConexion();

    $stmt = $conexion->prepare("SELECT * FROM reservas WHERE numero = ?");
    $stmt->execute([$numero]);
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($reserva) {
        return new Reservas(
            $reserva['numero'],
            $reserva['fecha_inicio'],
            $reserva['fecha_fin'],
            $reserva['cliente_dni'],
            $reserva['cabana_numero']
        );
    } else {
        return null;
    }
}

// Función para buscar reservas por DNI del cliente
function buscarReservasPorCliente($dni)
{
    $conexion = Database::obtenerInstancia()->obtenerConexion();

    $stmt = $conexion->prepare("SELECT * FROM reservas WHERE cliente_dni = ?");
    $stmt->execute([$dni]);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $listaReservas = [];
    foreach ($reservas as $reserva) {
        $listaReservas[] = new Reservas(
            $reserva['numero'],
            $reserva['fecha_inicio'],
            $reserva['fecha_fin'],
            $reserva['cliente_dni'],
            $reserva['cabana_numero']
        );
    }

    return $listaReservas;
}

// Función para buscar reservas por número de cabaña
function buscarReservasPorCabana($numero)
{
    $conexion = Database::obtenerInstancia()->obtenerConexion();

    $stmt = $conexion->prepare("SELECT * FROM reservas WHERE cabana_numero = ?");
    $stmt->execute([$numero]);
    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $listaReservas = [];
    foreach ($reservas as $reserva) {
        $listaReservas[] = new Reservas(
            $reserva['numero'],
            $reserva['fecha_inicio'],
            $reserva['fecha_fin'],
            $reserva['cliente_dni'],
            $reserva['cabana_numero']
        );
    }

    return $listaReservas;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Búsqueda de Reservas</title>
</head>
<body>
    <h1>Búsqueda de Reservas</h1>
    <form method="GET" action="resultadoreservas.php">
        <label for="numero_reserva">Número de Reserva:</label>
        <input type="text" id="numero_reserva" name="numero_reserva"><br><br>

        <label for="dni_cliente">DNI del Cliente:</label>
        <input type="text" id="dni_cliente" name="dni_cliente"><br><br>

        <label for="numero_cabana">Número de Cabaña:</label>
        <input type="text" id="numero_cabana" name="numero_cabana"><br><br>

        <input type="submit" value="Buscar Reserva">
    </form>
</body>
</html>

