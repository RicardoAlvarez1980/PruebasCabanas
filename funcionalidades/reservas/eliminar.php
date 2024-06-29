<?php

require_once './clases/Cabanas.php';
require_once './clases/Reservas.php';
require_once './clases/Clientes.php';

require_once __DIR__ . '/../../includes/Database.php';

class Eliminar
{
    // Método para eliminar una cabaña existente
    public static function eliminarCabana($numero)
    {
        // Obtener la conexión a la base de datos desde la instancia Singleton
        $conexion = Database::obtenerInstancia()->obtenerConexion();

        // Preparar la consulta SQL para eliminar la cabaña con el número proporcionado
        $stmt = $conexion->prepare("DELETE FROM cabanas WHERE numero = ?");

        // Ejecutar la consulta con el número proporcionado
        $stmt->execute([$numero]);
    }
}
