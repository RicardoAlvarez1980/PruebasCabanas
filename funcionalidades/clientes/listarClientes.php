<?php
require_once __DIR__ . '/../../includes/Database.php';


// Función para obtener clientes como objetos Clientes
function obtenerClientes()
{
    try {
        $db = Database::obtenerInstancia()->obtenerConexion();
        $query = "SELECT dni, nombre, direccion, telefono, email FROM clientes"; // Asegúrate de seleccionar las columnas específicas que necesitas
        $statement = $db->prepare($query);
        $statement->execute();
        $clientesData = $statement->fetchAll(PDO::FETCH_ASSOC);

        $clientes = [];
        foreach ($clientesData as $clienteData) {
            // Crear objeto Cliente y agregarlo al arreglo
            $cliente = new Clientes(
                $clienteData['dni'],
                $clienteData['nombre'],
                $clienteData['direccion'],
                $clienteData['telefono'],
                $clienteData['email']
            );
            $clientes[] = $cliente;
        }

        return $clientes;
    } catch (PDOException $e) {
        // Manejo de errores: puedes loguear el error o mostrar un mensaje genérico
        error_log("Error al obtener clientes desde la base de datos: " . $e->getMessage());
        return []; // Devolver un array vacío en caso de error
    }
}
?>

