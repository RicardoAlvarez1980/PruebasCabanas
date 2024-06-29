<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Clientes, Cabañas y Reservas</title>

    <!-- Enlace a Bootstrap CSS desde CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Listado de Clientes, Cabañas y Reservas</h1>

        <!-- PHP para obtener y mostrar datos -->
        <?php
        // Incluir el archivo de conexión a la base de datos
        require_once 'includes/Database.php';
        require_once 'clases/Clientes.php';
        require_once 'clases/Cabanas.php';
        require_once 'clases/Reservas.php';

        // Función para obtener clientes como objetos Clientes
        function obtenerClientes()
        {
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
        }

        // Función para obtener cabañas como objetos Cabanas
        function obtenerCabanas()
        {
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
        }


        // Función para obtener reservas
        function obtenerReservas()
        {
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
        }



        // Obtener datos
        $clientes = obtenerClientes();
        $cabanas = obtenerCabanas();
        $reservas = obtenerReservas();
        ?>

        <!-- Tabla de Clientes -->
        <h2>Listado de Clientes</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente) : ?>
                    <tr>
                        <td><?php echo $cliente->getDni(); ?></td>
                        <td><?php echo $cliente->getNombre(); ?></td>
                        <td><?php echo $cliente->getDireccion(); ?></td>
                        <td><?php echo $cliente->getTelefono(); ?></td>
                        <td><?php echo $cliente->getEmail(); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($clientes)) : ?>
                    <tr>
                        <td colspan="5">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tabla de Cabañas -->
        <h2>Listado de Cabañas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Capacidad</th>
                    <th>Descripción</th>
                    <th>Costo Diario</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cabanas as $cabana) : ?>
                    <tr>
                        <td><?php echo $cabana->getNumero(); ?></td>
                        <td><?php echo $cabana->getCapacidad(); ?></td>
                        <td><?php echo $cabana->getDescripcion(); ?></td>
                        <td><?php echo $cabana->getCostoDiario(); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($cabanas)) : ?>
                    <tr>
                        <td colspan="4">No hay cabañas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tabla de Reservas -->
        <h2>Listado de Reservas</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número Reserva</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Cliente</th>
                    <th>Cabaña</th>
                    <th>Días</th>
                    <th>Costo Diario</th>
                    <th>Costo Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva) : ?>
                    <tr>
                        <td><?php echo $reserva->getNumero(); ?></td>
                        <td><?php echo $reserva->getFechaInicio(); ?></td>
                        <td><?php echo $reserva->getFechaFin(); ?></td>
                        <td><?php echo $reserva->getCliente()->getNombre(); ?></td>
                        <td><?php echo $reserva->getCabana()->getNumero(); ?></td>
                        <td><?php echo $reserva->calcularDiferenciaDias(); ?></td>
                        <td><?php echo $reserva->getCabana()->getCostoDiario(); ?></td>
                        <td><?php echo $reserva->calcularCostoTotal(); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($reservas)) : ?>
                    <tr>
                        <td colspan="6">No hay reservas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
    <!-- Incluir jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- JS de Bootstrap (opcional, para funcionalidades como dropdowns, modales, etc.) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>