<?php

require_once './clases/Cabanas.php';
require_once './clases/Reservas.php';
require_once './clases/Clientes.php';

require_once __DIR__ . '/../../includes/Database.php';

class Altas
{

    public static function altaCabana($numero, $capacidad, $descripcion, $costo_diario)
    {
        // Obtener la instancia de la conexión a la base de datos
        $conexion = Database::obtenerInstancia()->obtenerConexion();

        // Preparar la consulta SQL para insertar una nueva cabaña
        $stmt = $conexion->prepare("INSERT INTO cabanas (numero, capacidad, descripcion, costo_diario) VALUES (?, ?, ?, ?)");

        // Ejecutar la consulta con los valores proporcionados
        $stmt->execute([$numero, $capacidad, $descripcion, $costo_diario]);
    }
}
