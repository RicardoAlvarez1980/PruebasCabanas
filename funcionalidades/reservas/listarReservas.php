<?php

require_once './clases/Cabanas.php';
require_once './clases/Reservas.php';
require_once './clases/Clientes.php';

require_once __DIR__ . '/../../includes/Database.php';

// Función para obtener reservas
function obtenerReservas()
{
    try {
        $db = Database::obtenerInstancia()->obtenerConexion();
        $query = "SELECT r.numero_reserva, r.fecha_inicio, r.fecha_fin, c.dni as cliente_dni, c.nombre as cliente_nombre, c.direccion as cliente_direccion, c.telefono as cliente_telefono, c.email as cliente_email, ca.numero as cabana_numero, ca.capacidad as cabana_capacidad, ca.descripcion as cabana_descripcion, ca.costo_diario as cabana_costo_diario
                  FROM Reservas r
                  INNER JOIN Clientes c ON r.cliente_dni = c.dni
                  INNER JOIN Cabanas ca ON r.cabana_numero = ca.numero";
        $statement = $db->prepare($query);
        $statement->execute();
        $reservasData = $statement->fetchAll(PDO::FETCH_ASSOC);

        $reservas = [];
        foreach ($reservasData as $reservaData) {
            $cliente = new Clientes(
                $reservaData['cliente_dni'],
                $reservaData['cliente_nombre'],
                $reservaData['cliente_direccion'],
                $reservaData['cliente_telefono'],
                $reservaData['cliente_email']
            );

            $cabana = new Cabanas(
                $reservaData['cabana_numero'],
                $reservaData['cabana_capacidad'],
                $reservaData['cabana_descripcion'],
                $reservaData['cabana_costo_diario']
            );

            // Crear objeto Reservas y agregarlo al arreglo
            $reserva = new Reservas(
                $reservaData['numero_reserva'],
                $reservaData['fecha_inicio'],
                $reservaData['fecha_fin'],
                $cliente,
                $cabana
            );
            $reservas[] = $reserva;
        }

        return $reservas;
    } catch (PDOException $e) {
        // Manejo de errores: puedes loguear el error o mostrar un mensaje genérico
        error_log("Error al obtener reservas desde la base de datos: " . $e->getMessage());
        return []; // Devolver un array vacío en caso de error
    }
}
    