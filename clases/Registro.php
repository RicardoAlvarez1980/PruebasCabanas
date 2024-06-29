<?php
// Incluir el archivo de conexión a la base de datos
require_once 'includes/Database.php';

// Verificar si se ha enviado el formulario (POST request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Obtener instancia de la conexión a la base de datos
        $db = Database::obtenerInstancia();
        $conexion = $db->obtenerConexion();

        // Preparar consulta SQL para insertar administrador
        $sql = "INSERT INTO Administradores (nombre, email, hashed_password) VALUES (:nombre, :email, :hashed_password)";
        $consulta = $conexion->prepare($sql);

        // Ejecutar la consulta con parámetros
        $consulta->execute([
            ':nombre' => $nombre,
            ':email' => $email,
            ':hashed_password' => $hashed_password
        ]);

        // Verificar si se ha insertado correctamente
        if ($consulta->rowCount() > 0) {
            echo "Administrador registrado correctamente.";
        } else {
            echo "Error al registrar el administrador.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
