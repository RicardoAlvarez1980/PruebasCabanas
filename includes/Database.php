<?php
class Database
{
    private static $instancia;
    private $conexion;

    private $host = 'localhost'; // Host de la base de datos local
    private $usuario = 'root'; // Usuario de la base de datos
    private $contrasena = ''; // Contraseña de la base de datos
    private $base_de_datos = 'cabinmanager'; // Nombre de la base de datos

    private function __construct()
    {
        try {
            // Establecer la conexión PDO a MySQL
            $this->conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->base_de_datos}",
                $this->usuario,
                $this->contrasena,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );

            // Habilitar excepciones PDO
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function obtenerInstancia()
    {
        if (self::$instancia == null) {
            self::$instancia = new Database();
        }
        return self::$instancia;
    }

    public function obtenerConexion()
    {
        return $this->conexion;
    }

    // Evita que la instancia sea clonada
    private function __clone()
    {
        throw new RuntimeException('La clonación de esta instancia no está permitida.');
    }
}
