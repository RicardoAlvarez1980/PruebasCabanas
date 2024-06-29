<?php

require_once './clases/Cabanas.php';
require_once './clases/Reservas.php';
require_once './clases/Clientes.php';
require_once __DIR__ . '/../../includes/Database.php';

class Modificar
{
// Método para modificar una cabaña existente
public static function modificarCabana($numero, $capacidad, $descripcion, $costo_diario)
{
    // Obtener la conexión a la base de datos desde la instancia Singleton
    $conexion = Database::obtenerInstancia()->obtenerConexion();

    // Preparar la consulta SQL para actualizar la cabaña con el número proporcionado
    $stmt = $conexion->prepare("UPDATE cabanas SET capacidad = ?, descripcion = ?, costo_diario = ? WHERE numero = ?");

    // Ejecutar la consulta con los valores proporcionados
    $stmt->execute([$capacidad, $descripcion, $costo_diario, $numero]);
}
}