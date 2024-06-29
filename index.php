<?php
// Incluir el archivo de conexión a la base de datos y clases necesarias
require_once 'includes/Database.php';

require_once 'funcionalidades/clientes/listarClientes.php';
require_once 'funcionalidades/reservas/listarReservas.php';
require_once 'funcionalidades/cabanas/listarCabanas.php';

// Obtener datos
$clientes = obtenerClientes();
$cabanas = obtenerCabanas();
$reservas = obtenerReservas();

// Verificar si se está enviando el formulario para agregar cliente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni = $_POST['dni'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];

    // Insertar en la base de datos
    $conexion = Database::obtenerInstancia()->obtenerConexion();
    $stmt = $conexion->prepare("INSERT INTO clientes (dni, nombre, direccion, telefono, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$dni, $nombre, $direccion, $telefono, $email]);

    // Recargar la página para actualizar la lista de clientes
    header("Location: index.php");
    exit();
}
?>

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

        <!-- Botón de agregar alineado a la derecha -->
        <div class="text-right mb-3">
            <!-- Enlaces a los formularios de alta -->
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalAgregarCliente">
                Agregar Cliente
            </button>
            <a href="funcionalidades/cabanas/altaCabanas.php" class="btn btn-success">Agregar Cabaña</a>
            <a href="funcionalidades/reservas/altaReservas.php" class="btn btn-success">Agregar Reserva</a>
        </div>

        <!-- Tabla de Clientes -->
        <h2>Listado de Clientes</h2>
        <table class="table table-striped">
            <!-- Encabezados de la tabla -->
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)) : ?>
                    <?php foreach ($clientes as $cliente) : ?>
                        <tr>
                            <td><?php echo $cliente->getDni(); ?></td>
                            <td><?php echo $cliente->getNombre(); ?></td>
                            <td><?php echo $cliente->getDireccion(); ?></td>
                            <td><?php echo $cliente->getTelefono(); ?></td>
                            <td><?php echo $cliente->getEmail(); ?></td>
                            <td>
                                <a href="funcionalidades/clientes/editarCliente.php?id=<?php echo htmlspecialchars($cliente->getDni()); ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="funcionalidades/clientes/eliminarCliente.php?id=<?php echo htmlspecialchars($cliente->getDni()); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="funcionalidades/clientes/verCliente.php?dni=<?php echo $cliente->getDni(); ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tabla de Cabañas -->
        <h2>Listado de Cabañas</h2>
        <table class="table table-striped">
            <!-- Encabezados de la tabla -->
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Capacidad</th>
                    <th>Descripción</th>
                    <th>Costo Diario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cabanas)) : ?>
                    <?php foreach ($cabanas as $cabana) : ?>
                        <tr>
                            <td><?php echo $cabana->getNumero(); ?></td>
                            <td><?php echo $cabana->getCapacidad(); ?></td>
                            <td><?php echo $cabana->getDescripcion(); ?></td>
                            <td><?php echo $cabana->getCostoDiario(); ?></td>
                            <td>
                                <a href="funcionalidades/cabanas/editarCabana.php?id=<?php echo htmlspecialchars($cabana->getNumero()); ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="funcionalidades/cabanas/eliminarCabana.php?id=<?php echo htmlspecialchars($cabana->getNumero()); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="funcionalidades/cabanas/verCabana.php?numero=<?php echo $cabana->getNumero(); ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">No hay cabañas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tabla de Reservas -->
        <h2>Listado de Reservas</h2>
        <table class="table table-striped">
            <!-- Encabezados de la tabla -->
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reservas)) : ?>
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
                            <td>
                                <a href="funcionalidades/reservas/editarReserva.php?id=<?php echo htmlspecialchars($reserva->getNumero()); ?>" class="btn btn-primary btn-sm">Editar</a>
                                <a href="funcionalidades/reservas/eliminarReserva.php?id=<?php echo htmlspecialchars($reserva->getNumero()); ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                <a href="funcionalidades/reservas/verReserva.php?numero=<?php echo $reserva->getNumero(); ?>" class="btn btn-info btn-sm">Ver Detalles</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9">No hay reservas registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <!-- Modal Agregar Cliente -->
    <div class="modal fade" id="modalAgregarCliente" tabindex="-1" role="dialog" aria-labelledby="modalAgregarClienteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarClienteLabel">Agregar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarCliente" method="POST" action="index.php">
                        <div class="form-group">
                            <label for="dni">DNI:</label>
                            <input type="text" class="form-control" id="dni" name="dni" required>
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="direccion">Dirección:</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Cliente</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir jQuery desde CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- JS de Bootstrap (para funcionalidades de modal) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
