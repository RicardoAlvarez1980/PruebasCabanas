<?php

require_once './clases/Cabanas.php';
require_once './clases/Reservas.php';
require_once './clases/Clientes.php';

require_once __DIR__ . '/../../includes/Database.php';
// Función para obtener cabañas como objetos Cabanas
function obtenerCabanas()
{
    try {
        $db = Database::obtenerInstancia()->obtenerConexion();
        $query = "SELECT numero, capacidad, descripcion, costo_diario FROM cabanas"; // Asegúrate de seleccionar las columnas específicas que necesitas
        $statement = $db->prepare($query);
        $statement->execute();
        $cabanasData = $statement->fetchAll(PDO::FETCH_ASSOC);

        $cabanas = [];
        foreach ($cabanasData as $cabanaData) {
            // Crear objeto Cabana y agregarlo al arreglo
            $cabana = new Cabanas(
                $cabanaData['numero'],
                $cabanaData['capacidad'],
                $cabanaData['descripcion'],
                $cabanaData['costo_diario']
            );
            $cabanas[] = $cabana;
        }

        return $cabanas;
    } catch (PDOException $e) {
        // Manejo de errores: puedes loguear el error o mostrar un mensaje genérico
        error_log("Error al obtener cabañas desde la base de datos: " . $e->getMessage());
        return []; // Devolver un array vacío en caso de error
    }
}

    